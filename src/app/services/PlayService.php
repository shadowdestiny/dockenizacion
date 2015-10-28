<?php


namespace EuroMillions\services;


use Doctrine\ORM\EntityManager;

use EuroMillions\components\CypherCastillo3DES;
use EuroMillions\entities\Bet;
use EuroMillions\entities\EuroMillionsDraw;
use EuroMillions\entities\LogValidationApi;
use EuroMillions\entities\Lottery;
use EuroMillions\entities\PlayConfig;
use EuroMillions\entities\User;
use EuroMillions\exceptions\InvalidBalanceException;
use EuroMillions\interfaces\ILotteryValidation;
use EuroMillions\interfaces\IPlayStorageStrategy;
use EuroMillions\repositories\BetRepository;
use EuroMillions\repositories\PlayConfigRepository;
use EuroMillions\services\external_apis\LotteryValidationCastilloApi;
use EuroMillions\vo\CastilloCypherKey;
use EuroMillions\vo\CastilloTicketId;
use EuroMillions\vo\EuroMillionsLine;
use EuroMillions\vo\PlayForm;
use EuroMillions\vo\PlayFormToStorage;
use EuroMillions\vo\ActionResult;
use EuroMillions\vo\UserId;

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
        $this->playConfigRepository = $entityManager->getRepository('EuroMillions\entities\PlayConfig');
        $this->betRepository = $entityManager->getRepository('EuroMillions\entities\Bet');
        $this->lotteryRepository = $this->entityManager->getRepository('EuroMillions\entities\Lottery');
        $this->lotteriesDataService = $lotteriesDataService;
        $this->playStorageStrategy = $playStorageStrategy;
        $this->userRepository = $entityManager->getRepository('EuroMillions\entities\User');
        $this->logValidationRepository = $entityManager->getRepository('EuroMillions\entities\LogValidationApi');
    }

    /**
     * @param User $user
     * @return ActionResult
     */
    public function play(User $user)
    {
        //EMTD previously should check amount or userservice deduct amount about his balance
        if($user->getBalance()->getAmount() > 0){
            try {
                $temporalForm = $this->playStorageStrategy->findByKey($user->getId()->id());
                if(empty($temporalForm)){
                    return new ActionResult(false,'The search key doesn\'t exist');
                }
                $playConfig = new PlayConfig();
                $playConfig->formToEntity($user,$temporalForm);
                $this->playConfigRepository->add($user, $playConfig);
                $this->entityManager->flush($playConfig);
                //Remove play storage
                $result = $this->playStorageStrategy->delete($user->getId()->id());
                if(!empty($result)){
                    return new ActionResult(true);
                }else{
                    return new ActionResult(false);
                }
            } catch(\Exception $e){
                return new ActionResult(false, 'An exception occurred while created play');
            }
        } else {
            return new ActionResult(false);
        }
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
            $result = $this->betRepository->getBetsByDrawDate(new \DateTime($dateNextDraw));
            if(!empty($result)){
                return new ActionResult(true);
            }else{
                try{
                    $bet = new Bet($playConfig,$euroMillionsDraw);
                    $castillo_key = CastilloCypherKey::create();
                    $castillo_ticket = CastilloTicketId::create();
                    $result_validation = $lotteryValidation->validateBet($bet,new CypherCastillo3DES(),$castillo_key,$castillo_ticket,new \DateTime($dateNextDraw));

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
                        $user->setBalance($user->getBalance()->subtract($single_bet_price));
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

}