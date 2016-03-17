<?php
namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;

use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;

use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\interfaces\IPlayStorageStrategy;
use EuroMillions\web\repositories\BetRepository;
use EuroMillions\web\repositories\PlayConfigRepository;
use EuroMillions\web\services\card_payment_providers\PayXpertCardPaymentStrategy;
use EuroMillions\web\services\external_apis\LotteryValidationCastilloApi;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\Order;
use EuroMillions\web\vo\PlayFormToStorage;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\UserId;
use Money\Money;

class PlayService
{

    private $entityManager;

    /**
     * @var PlayConfigRepository
     */
    private $playConfigRepository;

    /** @var  LotteryService */
    private $lotteryService;

    /** @var  BetRepository */
    private $betRepository;

    private $lotteryRepository;

    /** @var IPlayStorageStrategy */
    private $playStorageStrategy;

    private $orderStorageStrategy;

    private $userRepository;

    private $logValidationRepository;

    /** @var CartService $cartService*/
    private $cartService;
    /** @var  WalletService $walletService */
    private $walletService;
    /** @var  PayXpertCardPaymentStrategy $payXpertCardPaymentStrategy */
    private $payXpertCardPaymentStrategy;
    /** @var  BetService $betService */
    private $betService;

    public function __construct(EntityManager $entityManager,
                                LotteryService $lotteryService,
                                IPlayStorageStrategy $playStorageStrategy,
                                IPlayStorageStrategy $orderStorageStrategy,
                                CartService $cartService,
                                WalletService $walletService,
                                ICardPaymentProvider $payXpertCardPaymentStrategy,
                                BetService $betService)
    {
        $this->entityManager = $entityManager;
        $this->playConfigRepository = $entityManager->getRepository('EuroMillions\web\entities\PlayConfig');
        $this->betRepository = $entityManager->getRepository('EuroMillions\web\entities\Bet');
        $this->lotteryRepository = $this->entityManager->getRepository('EuroMillions\web\entities\Lottery');
        $this->lotteryService = $lotteryService;
        $this->playStorageStrategy = $playStorageStrategy;
        $this->orderStorageStrategy = $orderStorageStrategy;
        $this->userRepository = $entityManager->getRepository('EuroMillions\web\entities\User');
        $this->logValidationRepository = $entityManager->getRepository('EuroMillions\web\entities\LogValidationApi');
        $this->cartService = $cartService;
        $this->walletService = $walletService;
        $this->betService = $betService;
        $this->payXpertCardPaymentStrategy = $payXpertCardPaymentStrategy;
        // EMTD: @rmrbest tantas dependencias dan tufillo a que necesita refactorizar.
    }

    public function getPlaysFromGuestUserAndSwitchUser(UserId $user_id, UserId $current_user_id)
    {
        /** @var User $user */
        $user = $this->userRepository->find($current_user_id);
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
                    foreach($form_decode->play_config as $bet) {
                        $playConfig = new PlayConfig();
                        $playConfig->formToEntity($user,$bet,$bet->euroMillionsLines);
                        $bets[] = $playConfig;
                    }
                    return new ActionResult(true,$bets);
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


    /**
     * @param User $user
     * @return ActionResult
     */
    public function play( UserId $user_id, Money $funds = null, CreditCard $credit_card = null)
    {
        if($user_id) {
            try{
                /** @var User $user */
                $user = $this->userRepository->find(['id' => $user_id]);
                $result_order = $this->cartService->get($user_id);
                if( $result_order->success() ) {
                    /** @var Order $order */
                    $order = $result_order->getValues();
                    $order->addFunds($funds);
                    $draw = $this->lotteryService->getNextDrawByLottery('EuroMillions');
                    if( null != $credit_card ) {
                        $result_payment = $this->walletService->rechargeWithCreditCard($this->payXpertCardPaymentStrategy,$credit_card, $user, $order->getCreditCardCharge());
                    } else {
                        $wallet = $user->getWallet();
                        $wallet->payPreservingWinnings($order->getCreditCardCharge()->getNetAmount());
                        $user->setWallet($wallet);
                        $this->userRepository->add($user);
                        $this->entityManager->flush();
                        $result_payment = new ActionResult(true,$order);
                    }
                    if( count($order->getPlayConfig()) > 0 ) {
                        foreach( $order->getPlayConfig() as $play_config ) {
                            $this->playConfigRepository->add($play_config);
                            $this->entityManager->flush($play_config);
                        }
                    }
                    if($order->isNextDraw($draw->getValues()->getDrawDate()) &&
                        $result_payment->success()){
                        foreach( $order->getPlayConfig() as $play_config ) {
                            $result_validation = $this->betService->validation($play_config, $draw->getValues());
                            if(!$result_validation->success()) {
                                return new ActionResult(false, $result_validation->errorMessage());
                            }
                        }
                        return new ActionResult(true,$order);
                    } else {
                        return new ActionResult($result_payment->success(), $order);
                    }
                } else {
                    //error
                }
            } catch ( \Exception $e ) {

            }
        }
        return new ActionResult(false);
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

    }

    public function getPlaysFromTemporarilyStorage(User $user)
    {
        try {
            /** @var ActionResult $result */
            $result = $this->playStorageStrategy->findByKey($user->getId()->id());
            if($result->success()) {
                $form_decode = json_decode($result->returnValues());
                $bets = [];
                foreach($form_decode->play_config as $bet) {
                    $playConfig = new PlayConfig();
                    $playConfig->formToEntity($user,$bet,$bet->euroMillionsLines);
                    $bets[] = $playConfig;
                }
                return new ActionResult(true,$bets);
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

    public function removeStorePlay( UserId $user_id )
    {
        if( null != $user_id ) {
            $this->playStorageStrategy->delete($user_id);
        }
    }

    public function removeStoreOrder( UserId $user_id )
    {
        if( null != $user_id ) {
            $this->orderStorageStrategy->delete($user_id);
        }
    }

}