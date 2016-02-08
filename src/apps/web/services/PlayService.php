<?php


namespace EuroMillions\web\services;


use Doctrine\ORM\EntityManager;

use EuroMillions\web\components\CypherCastillo3DES;
use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\LogValidationApi;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\exceptions\InvalidBalanceException;
use EuroMillions\web\interfaces\IPlayStorageStrategy;
use EuroMillions\web\repositories\BetRepository;
use EuroMillions\web\repositories\PlayConfigRepository;
use EuroMillions\web\services\external_apis\LotteryValidationCastilloApi;
use EuroMillions\web\vo\CastilloCypherKey;
use EuroMillions\web\vo\CastilloTicketId;
use EuroMillions\web\vo\PlayFormToStorage;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\UserId;
use Money\Currency;
use Money\Money;

class PlayService
{

    private $entityManager;

    /**
     * @var PlayConfigRepository
     */
    private $playConfigRepository;

    /** @var  LotteriesDataService */
    private $lotteriesDataService;

    /** @var  BetRepository */
    private $betRepository;

    private $lotteryRepository;

    private $playStorageStrategy;

    private $userRepository;

    private $logValidationRepository;


    public function __construct(EntityManager $entityManager, LotteriesDataService $lotteriesDataService, IPlayStorageStrategy $playStorageStrategy )
    {
        $this->entityManager = $entityManager;
        $this->playConfigRepository = $entityManager->getRepository('EuroMillions\web\entities\PlayConfig');
        $this->betRepository = $entityManager->getRepository('EuroMillions\web\entities\Bet');
        $this->lotteryRepository = $this->entityManager->getRepository('EuroMillions\web\entities\Lottery');
        $this->lotteriesDataService = $lotteriesDataService;
        $this->playStorageStrategy = $playStorageStrategy;
        $this->userRepository = $entityManager->getRepository('EuroMillions\web\entities\User');
        $this->logValidationRepository = $entityManager->getRepository('EuroMillions\web\entities\LogValidationApi');

    }



    public function getPlaysFromGuestUserAndSwitchUser(UserId $user_id, UserId $current_user_id)
    {

        /** @var User $user */
        $user = $this->userRepository->find($user_id->id());
        if( null == $user ) {
            return new ActionResult(false);
        }

        try{
            /** @var ActionResult $result_save_playstorage */
            $result_save_playstorage = $this->playStorageStrategy->findByKey($user_id);
            if($result_save_playstorage->success()) {
                $this->playStorageStrategy->save($result_save_playstorage->returnValues(),$current_user_id);
                $result_save_playstorage = $this->playStorageStrategy->findByKey($current_user_id);
                if($result_save_playstorage->success()) {
                    $form_decode = json_decode($result_save_playstorage->getValues());
                    $bets = [];
                    foreach($form_decode->euroMillionsLines->bets as $bet) {
                        $bets[] = $bet;
                    }
                    $playConfig = new PlayConfig();
                    $playConfig->formToEntity($user,$result_save_playstorage->getValues(),$bets);
                    return new ActionResult(true,$playConfig);
                } else {
                    return new ActionResult(false);
                }
            } else {
                return new ActionResult(false);
            }
        } catch ( \Exception $e ) {
            return new ActionResult(false);
        }
    }

    //EMTD pass Order VO instead stdClass
    public function getTotalPriceFromPlay(PlayConfig $playConfig, Money $single_bet_price)
    {
        try{
            $lines = $playConfig->getLine();
            $total =  count($lines)
            * $playConfig->getDrawDays()->value() * $single_bet_price->getAmount() * $playConfig->getFrequency();

            return new Money((int) $total, new Currency('EUR'));
        } catch ( \Exception $e ) {
            throw new \Exception('Error calculating total price');
        }
    }


    /**
     * @param User $user
     * @return ActionResult
     */
    public function play(User $user )
    {
//        //EMTD previously should check amount or userservice deduct amount about his balance
//      //  if($user->getBalance()->getAmount() > 0){
//        try {
//            /** @var ActionResult $temporal_form_result */
//            $temporal_form_result = $this->playStorageStrategy->findByKey($user->getId()->id());
//            if(!$temporal_form_result->success()){
//                return new ActionResult(false,'The search key doesn\'t exist');
//            }
//            $form_decode = json_decode($temporal_form_result->getValues());
//            foreach($form_decode->euroMillionsLines->bets as $bet) {
//                $playConfig = new PlayConfig();
//                $playConfig->formToEntity($user,$temporal_form_result->getValues(),$bet);
//                $this->playConfigRepository->add($playConfig);
//                $this->entityManager->flush();
//                //Remove play storage
//                $result = $this->playStorageStrategy->delete($user->getId()->id());
//            }
//            if(!empty($result)){
//                return new ActionResult(true);
//            }else{
//                return new ActionResult(false);
//            }
//        } catch(\Exception $e){
//            return new ActionResult(false, 'An exception occurred while created play');
//            //EMTD send a warning to admin
//        }
//        } else {
//            return new ActionResult(false);
//        }
    }

    /**
     * @param PlayConfig $playConfig
     * @param EuroMillionsDraw $euroMillionsDraw
     * @param \DateTime $today
     * @param LotteryValidationCastilloApi $lotteryValidation
     * @return ActionResult
     */
    public function bet(PlayConfig $playConfig, EuroMillionsDraw $euroMillionsDraw, \DateTime $today = null, LotteryValidationCastilloApi $lotteryValidation = null)
    {
        if (!$today) {
            $today = new \DateTime();
        }

        if(!$lotteryValidation) {
            $lotteryValidation = new LotteryValidationCastilloApi();
        }
        /** @var User $user */
        $user = $this->userRepository->find($playConfig->getUser()->getId()->id());
        $single_bet_price = $euroMillionsDraw->getLottery()->getSingleBetPrice();
        if($user->getBalance()->getAmount() > $single_bet_price->getAmount()) {
            $dateNextDraw = $this->lotteriesDataService->getNextDateDrawByLottery('EuroMillions', $today);
            $result = $this->betRepository->getBetsByDrawDate($dateNextDraw);
            if(!empty($result)){
                return new ActionResult(true);
            }else{
                try{
                    $bet = new Bet($playConfig,$euroMillionsDraw);
                    $castillo_key = CastilloCypherKey::create();
                    $castillo_ticket = CastilloTicketId::create();
                    $bet->setCastilloBet($castillo_ticket);
                    $result_validation = $lotteryValidation->validateBet($bet,new CypherCastillo3DES(),$castillo_key,$castillo_ticket,$dateNextDraw);
                    $log_api_reponse = new LogValidationApi();
                    $log_api_reponse->initialize([
                        'id_provider' => 1,
                        'id_ticket' => $lotteryValidation->getXmlResponse()->id,
                        'status' => $lotteryValidation->getXmlResponse()->status,
                        'response' => $lotteryValidation->getXmlResponse(),
                        'received' => new \DateTime()
                    ]);

                    $this->logValidationRepository->add($log_api_reponse);
                    $this->entityManager->flush($log_api_reponse);
                    if($result_validation->success()) {
                        $this->betRepository->add($bet);
                        $user->payPreservingWinnings($single_bet_price);
                        $this->userRepository->add($user);
                        $playConfig->setActive(false);
                        $this->playConfigRepository->add($playConfig);
                        $this->entityManager->flush();
                        return new ActionResult(true);
                    } else{
                        return new ActionResult(false, $result_validation->errorMessage());
                    }
                }catch(\Exception $e) {
                    return new ActionResult(false);
                }
            }
        } else {
            throw new InvalidBalanceException();
        }
    }

    public function getPlaysFromTemporarilyStorage(User $user)
    {
        try {
            /** @var ActionResult $result */
            $result = $this->playStorageStrategy->findByKey($user->getId()->id());
            if($result->success()) {
                $form_decode = json_decode($result->returnValues());
                $bets = [];
                foreach($form_decode->euroMillionsLines->bets as $bet) {
                    $bets[] = $bet;
                }
                $playConfig = new PlayConfig();
                $playConfig->formToEntity($user,$result->getValues(),$bets);
                return new ActionResult(true,$playConfig);
            } else {
                return new ActionResult(false);
            }
        } catch ( \RedisException $r) {
            return new ActionResult(false, $r->getMessage());
        }

    }

    public function savePlayFromJson($json, UserId $userId)
    {
        $result = $this->playStorageStrategy->save($json,$userId);
        if($result->success()){
            return new ActionResult(true);
        }else{
            return new ActionResult(false);
        }
    }


    public function temporarilyStorePlay(PlayFormToStorage $playForm,UserId $userId)
    {
        $result = $this->playStorageStrategy->saveAll($playForm,$userId);
        if($result->success()){
            return new ActionResult(true);
        }else{
            return new ActionResult(false);
        }
    }

    public function getPlaysConfigToBet(\DateTime $date)
    {
        $result = $this->playConfigRepository->getPlayConfigsByDrawDayAndDate($date);
        if(!empty($result)){
            return new ActionResult(true,$result);
        }else{
            return new ActionResult(false);
        }
    }

    public function getPlayConfigWithLongEnded(\DateTime $date)
    {
        $result = $this->playConfigRepository->getPlayConfigsLongEnded($date);
        if(!empty($result)) {
            return new ActionResult(true,$result);
        }else {
            return new ActionResult(false);
        }
    }
}