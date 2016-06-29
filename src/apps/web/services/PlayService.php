<?php
namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;

use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\PurchaseConfirmationEmailTemplate;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\interfaces\IPlayStorageStrategy;
use EuroMillions\web\repositories\PlayConfigRepository;
use EuroMillions\web\repositories\UserRepository;
use EuroMillions\web\services\card_payment_providers\PayXpertCardPaymentStrategy;
use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\enum\TransactionType;
use EuroMillions\web\vo\Order;
use EuroMillions\web\vo\PlayFormToStorage;
use EuroMillions\shared\vo\results\ActionResult;
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

    /** @var IPlayStorageStrategy */
    private $playStorageStrategy;

    private $orderStorageStrategy;

    /** @var UserRepository $userRepository  */
    private $userRepository;

    /** @var CartService $cartService*/
    private $cartService;
    /** @var  WalletService $walletService */
    private $walletService;
    /** @var  PayXpertCardPaymentStrategy $payXpertCardPaymentStrategy */
    private $payXpertCardPaymentStrategy;
    /** @var  BetService $betService */
    private $betService;
    /** @var  EmailService $emailService */
    private $emailService;


    //EMTD refactor this class: a lot of dependencies
    public function __construct(EntityManager $entityManager,
                                LotteryService $lotteryService,
                                IPlayStorageStrategy $playStorageStrategy,
                                IPlayStorageStrategy $orderStorageStrategy,
                                CartService $cartService,
                                WalletService $walletService,
                                ICardPaymentProvider $payXpertCardPaymentStrategy,
                                BetService $betService,
                                EmailService $emailService)
    {
        $this->entityManager = $entityManager;
        $this->playConfigRepository = $entityManager->getRepository('EuroMillions\web\entities\PlayConfig');
        $this->lotteryService = $lotteryService;
        $this->playStorageStrategy = $playStorageStrategy;
        $this->orderStorageStrategy = $orderStorageStrategy;
        $this->userRepository = $entityManager->getRepository('EuroMillions\web\entities\User');
        $this->cartService = $cartService;
        $this->walletService = $walletService;
        $this->betService = $betService;
        $this->payXpertCardPaymentStrategy = $payXpertCardPaymentStrategy;
        $this->emailService = $emailService;
    }

    public function getPlaysFromGuestUserAndSwitchUser($user_id, $current_user_id)
    {
        /** @var User $user */
        $user = $this->userRepository->find($current_user_id);
        if( null == $user ) {
            return new ActionResult(false);
        }
        try{
            /** @var ActionResult $result_find_playstorage */
            $result_find_playstorage = $this->playStorageStrategy->findByKey($user_id);
            if($result_find_playstorage->success()) {
                $this->playStorageStrategy->save($result_find_playstorage->returnValues(),$current_user_id);
                $result_save_playstorage = $this->playStorageStrategy->findByKey($current_user_id);
                if($result_save_playstorage->success()) {
                    $form_decode = json_decode($result_find_playstorage->getValues());
                    $bets = [];
                    foreach($form_decode->play_config as $bet) {
                        $playConfig = new PlayConfig();
                        $playConfig->formToEntity($user,$bet,$bet->euroMillionsLines);
                        $playConfig->setLottery($this->getLottery());
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
     * @param $user_id
     * @param Money $funds
     * @param CreditCard $credit_card
     * @param bool $withAccountBalance
     * @return ActionResult
     */
    public function play( $user_id, Money $funds = null, CreditCard $credit_card = null, $withAccountBalance = false)
    {

        if($user_id) {
            try{
                $lottery = $this->lotteryService->getLotteryConfigByName('EuroMillions');
                /** @var User $user */
                $user = $this->userRepository->find(['id' => $user_id]);
                $result_order = $this->cartService->get($user_id);
                if( $result_order->success() ) {
                    /** @var Order $order */
                    $order = $result_order->getValues();
                    $order->setIsCheckedWalletBalance($withAccountBalance);
                    $order->addFunds($funds);
                    $order->setAmountWallet($user->getWallet()->getBalance());
                    $draw = $this->lotteryService->getNextDrawByLottery('EuroMillions');
                    if( null != $credit_card ) {
                        $this->payXpertCardPaymentStrategy->user($user);
                        $result_payment = $this->walletService->payWithCreditCard($this->payXpertCardPaymentStrategy,$credit_card, $user, $order->getCreditCardCharge());
                    } else {
                        $result_payment = new ActionResult(true,$order);
                    }

                    if( count($order->getPlayConfig()) > 0  && $result_payment->success()) {
                        //EMTD be careful now, set explicity lottery, but it should come inform on playconfig entity
                        /** @var PlayConfig $play_config */
                        foreach( $order->getPlayConfig() as $play_config ) {
                            $play_config->setLottery($lottery);
                            $this->playConfigRepository->add($play_config);
                            $this->entityManager->flush($play_config);
                        }
                    }
                    $orderIsToNextDraw = $order->isNextDraw($draw->getValues()->getDrawDate());
                    if( $result_payment->success() && $orderIsToNextDraw) {
                        $walletBefore = $user->getWallet();
                        foreach( $order->getPlayConfig() as $play_config ) {
                            $result_validation = $this->betService->validation($play_config, $draw->getValues(),$lottery->getNextDrawDate());
                            if(!$result_validation->success()) {
                                return new ActionResult(false, $result_validation->errorMessage());
                            }
                            $this->walletService->payWithWallet($user,$play_config);
                        }
                        $dataTransaction = [
                            'lottery_id' => 1,
                            'numBets' => count($order->getPlayConfig()),
                            'feeApplied' => $order->getCreditCardCharge()->getIsChargeFee(),
                            'amountWithWallet' => $lottery->getSingleBetPrice()->multiply(count($order->getPlayConfig()))->getAmount(),
                            'walletBefore' => $walletBefore,
                            'amountWithCreditCard' => 0,
                            'playConfigs' => array_map(function($val){return $val->getId();}, $order->getPlayConfig())
                        ];

                        $this->walletService->purchaseTransactionGrouped($user,TransactionType::TICKET_PURCHASE,$dataTransaction);
                        $this->sendEmailPurchase($user,$order->getPlayConfig());
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


    //EMTD método temporal, no hay unit testing. Copia del método play sin credit card
    public function playWithEmPlay($userId)
    {
        try {
            $lottery = $this->lotteryService->getLotteryConfigByName('EuroMillions');
            /** @var User $user */
            $user = $this->userRepository->find(['id' => $userId]);
            $result_order = $this->cartService->get($userId);
            if( $result_order->success() ) {
                /** @var Order $order */
                $order = $result_order->getValues();
                $order->setIsCheckedWalletBalance(false);
                $order->addFunds(null);
                $order->setAmountWallet($user->getWallet()->getBalance());
                $draw = $this->lotteryService->getNextDrawByLottery('EuroMillions');
                $result_payment = new ActionResult(true, $order);
                if (count($order->getPlayConfig()) > 0 && $result_payment->success()) {
                    /** @var PlayConfig $play_config */
                    foreach ($order->getPlayConfig() as $play_config) {
                        $play_config->setLottery($lottery);
                        $this->playConfigRepository->add($play_config);
                        $this->entityManager->flush($play_config);
                    }
                }
                $orderIsToNextDraw = $order->isNextDraw($draw->getValues()->getDrawDate());
                if ($result_payment->success() && $orderIsToNextDraw) {
                    $walletBefore = $user->getWallet();
                    foreach ($order->getPlayConfig() as $play_config) {
                        $result_validation = $this->betService->validation($play_config, $draw->getValues(), $lottery->getNextDrawDate());
                        if (!$result_validation->success()) {
                            return new ActionResult(false, $result_validation->errorMessage());
                        }
                        $this->walletService->payWithWallet($user, $play_config);
                    }
                    $dataTransaction = [
                        'lottery_id'           => 1,
                        'numBets'              => count($order->getPlayConfig()),
                        'feeApplied'           => $order->getCreditCardCharge()->getIsChargeFee(),
                        'amountWithWallet'     => $lottery->getSingleBetPrice()->multiply(count($order->getPlayConfig()))->getAmount(),
                        'walletBefore'         => $walletBefore,
                        'amountWithCreditCard' => 0,
                        'playConfigs'          => array_map(function ($val) {
                            return $val->getId();
                        }, $order->getPlayConfig())
                    ];

                    $this->walletService->purchaseTransactionGrouped($user, TransactionType::TICKET_PURCHASE, $dataTransaction);
                    $this->sendEmailPurchase($user, $order->getPlayConfig());
                    return new ActionResult(true, $order);
                } else {
                    return new ActionResult($result_payment->success(), $order);
                }
            } else {
                return new ActionResult(false);
            }
        } catch (\Exception $e) {
            return new ActionResult(false);
        }
    }

    public function getPlaysFromTemporarilyStorage(User $user)
    {
        try {
            /** @var ActionResult $result */
            $result = $this->playStorageStrategy->findByKey($user->getId());
            if($result->success()) {
                $form_decode = json_decode($result->returnValues());
                $bets = [];
                foreach($form_decode->play_config as $bet) {
                    $playConfig = new PlayConfig();
                    $playConfig->formToEntity($user,$bet,$bet->euroMillionsLines);
                    $playConfig->setLottery($this->getLottery());
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

    public function savePlayFromJson($json, $userId)
    {
        $result = $this->playStorageStrategy->save($json,$userId);
        if($result->success()){
            return new ActionResult(true);
        }else{
            return new ActionResult(false);
        }
    }


    public function temporarilyStorePlay(PlayFormToStorage $playForm, $userId)
    {
        $result = $this->playStorageStrategy->saveAll($playForm, $userId);
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

    public function removeStorePlay( $user_id )
    {
        if( null != $user_id ) {
            $this->playStorageStrategy->delete($user_id);
        }
    }

    public function removeStoreOrder( $user_id )
    {
        if( null != $user_id ) {
            $this->orderStorageStrategy->delete($user_id);
        }
    }

    //EMTD workaround, now only once lottery we have. In the future should pass lottery as param
    private function getLottery()
    {
        return $this->lotteryService->getLotteryConfigByName('EuroMillions');
    }

    private function sendEmailPurchase(User $user, $orderLines)
    {
        $emailBaseTemplate = new EmailTemplate();
        $emailTemplate = new PurchaseConfirmationEmailTemplate($emailBaseTemplate, new JackpotDataEmailTemplateStrategy($this->lotteryService));
        $emailTemplate->setLine($orderLines);
        $emailTemplate->setUser($user);
        $this->emailService->sendTransactionalEmail($user, $emailTemplate);
    }


}