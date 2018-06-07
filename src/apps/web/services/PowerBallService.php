<?php

namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;

use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\PurchaseConfirmationChristmasEmailTemplate;
use EuroMillions\web\emailTemplates\PurchaseConfirmationEmailTemplate;
use EuroMillions\web\emailTemplates\PurchaseSubscriptionConfirmationEmailTemplate;
use EuroMillions\web\entities\ChristmasTickets;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\interfaces\IPlayStorageStrategy;
use EuroMillions\web\repositories\PlayConfigRepository;
use EuroMillions\web\repositories\UserRepository;
use EuroMillions\web\services\card_payment_providers\PayXpertCardPaymentStrategy;
use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\Discount;
use EuroMillions\web\vo\dto\BundlePlayCollectionDTO;
use EuroMillions\web\vo\dto\BundlePlayDTO;
use EuroMillions\web\vo\enum\TransactionType;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\web\vo\EuroMillionsRegularNumber;
use EuroMillions\web\vo\Order;
use EuroMillions\web\vo\OrderChristmas;
use EuroMillions\web\vo\PlayFormToStorage;
use EuroMillions\shared\vo\results\ActionResult;
use Money\Currency;
use Money\Money;

class PowerBallService
{
    const NUM_BETS_PER_REQUEST = 5;

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

    /** @var UserRepository $userRepository */
    private $userRepository;

    /** @var CartService $cartService */
    private $cartService;
    /** @var  WalletService $walletService */
    private $walletService;
    /** @var  PayXpertCardPaymentStrategy $cardPaymentProvider */
    private $cardPaymentProvider;
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
        $this->cardPaymentProvider = $payXpertCardPaymentStrategy;
        $this->emailService = $emailService;
    }

    public function getPlaysFromGuestUserAndSwitchUser($user_id, $current_user_id, $lottery)
    {
        /** @var User $user */
        $user = $this->userRepository->find($current_user_id);
        if (null == $user) {
            return new ActionResult(false);
        }
        try {
            /** @var ActionResult $result_find_playstorage */
            $result_find_playstorage = $this->playStorageStrategy->findByKey($user_id);
            if ($result_find_playstorage->success()) {
                $this->playStorageStrategy->save($result_find_playstorage->returnValues(), $current_user_id);
                $result_save_playstorage = $this->playStorageStrategy->findByKey($current_user_id);
                if ($result_save_playstorage->success()) {
                    $form_decode = json_decode($result_find_playstorage->getValues());
                    $bets = [];
                    foreach ($form_decode->play_config as $bet) {
                        $playConfig = new PlayConfig();
                        $playConfig->formToEntity($user, $bet, $bet->euroMillionsLines);
                        $playConfig->setLottery($this->getLottery($lottery));
                        $playConfig->setDiscount(new Discount($bet->frequency, $this->playConfigRepository->retrieveEuromillionsBundlePrice()));
                        $bets[] = $playConfig;
                    }
                    return new ActionResult(true, $bets);
                } else {
                    return new ActionResult(false);
                }
            } else {
                return new ActionResult(false);
            }
        } catch (\Exception $e) {
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
    public function play($user_id, Money $funds = null, CreditCard $credit_card = null, $withAccountBalance = false, $isWallet = null)
    {

        if ($user_id) {
            try {
                $di = \Phalcon\Di::getDefault();
                /** @var Lottery $lottery */
                $lottery = $this->lotteryService->getLotteryConfigByName('PowerBall');

                /** @var User $user */
                $user = $this->userRepository->find(['id' => $user_id]);
                $powerPlay = $this->playStorageStrategy->findByKey($user_id);
                $powerPlay = (int)json_decode($powerPlay->returnValues())->play_config[0]->powerPlay;
                $result_order = $this->cartService->get($user_id, $lottery->getName());

                if ($result_order->success()) {

                    $order = $result_order->getValues();
                    if (is_null($credit_card) && $withAccountBalance ) {
                        if ($order->totalOriginal()->getAmount() > $user->getBalance()->getAmount()) {
                            return new ActionResult(false);
                        }
                    }
                    $discount = $order->getDiscount()->getValue();
                    $order->setIsCheckedWalletBalance($withAccountBalance);
//                    var_dump($lottery);die();
                    $order->setPowerPlayPrice($lottery->getPowerPlayValue());
                    $order->setPowerPlay($powerPlay);

                    $order->addFunds($order->getTotal());
                    $order->setAmountWallet($user->getWallet()->getBalance());


                    $draw = $this->lotteryService->getNextDrawByLottery('PowerBall');
                    if ($credit_card != null) {
                        $this->cardPaymentProvider->user($user);
                        $uniqueId = $this->walletService->getUniqueTransactionId();
                        $this->cardPaymentProvider->idTransaction = $uniqueId;
                        $result_payment = $this->walletService->payWithCreditCard($this->cardPaymentProvider, $credit_card, $user, $uniqueId, $order, $isWallet);
                    } else {
                        $result_payment = new ActionResult(true, $order);
                    }
                    if (count($order->getPlayConfig()) > 0 && $result_payment->success()) {
                        //EMTD be careful now, set explicity lottery, but it should come inform on playconfig entity
                        /** @var PlayConfig $play_config */
                        foreach ($order->getPlayConfig() as $play_config) {

                            $play_config->setLottery($lottery);
                            $play_config->setDiscount($order->getDiscount());
                            $play_config->setPowerPlay($powerPlay);
                            $this->playConfigRepository->add($play_config);
                            $this->entityManager->flush($play_config);
                        }
                    }

                    $APIPlayConfigs = json_encode($order->getPlayConfig());

                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, 'http://lotteriesbeta.euromillions.com/powerball/tickets/book/');
                    curl_setopt($curl, CURLOPT_POST, TRUE);
                    curl_setopt($curl, CURLOPT_POSTFIELDS,$APIPlayConfigs);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    $result = curl_exec($curl);

                    curl_close($curl);
                    $result_validation = json_decode($result);

                    $formPlay = null;

                    $orderIsToNextDraw = $order->isNextDraw($draw->getValues()->getDrawDate());
                    if ($result_payment->success() && $orderIsToNextDraw) {
                        $walletBefore = $user->getWallet();
                        $config = $di->get('config');
                        if ($config->application->send_single_validations) {

                            foreach ($order->getPlayConfig() as $play_config) {
                                $this->betService->validationLottoRisq($play_config, $draw->getValues(), $lottery->getNextDrawDate(), null, $result_validation->uuid);

                                if (!$result_validation->success) {
                                    return new ActionResult(false, $result_validation->errorMessage());
                                }
                                if ($order->getHasSubscription()) {
                                    if ($isWallet) {
                                        $this->walletService->paySubscriptionWithWallet($user, $play_config);
                                        $this->walletService->payWithSubscription($user, $play_config);
                                    } elseif ($withAccountBalance) {
                                        $this->walletService->payWithSubscription($user, $play_config);
                                        $this->walletService->paySubscriptionWithWalletAndCreditCard($user, $play_config);
                                    } else {
                                        $this->walletService->payWithSubscription($user, $play_config);
                                    }
                                } else {
                                    $this->walletService->payWithWallet($user, $play_config);
                                }
                            }

                            $numPlayConfigs = count($order->getPlayConfig());
                        } else {
                            $playConfigs = $order->getPlayConfig();
                            foreach (array_chunk($playConfigs, self::NUM_BETS_PER_REQUEST) as $playConfigsSplit) {
                                $this->betService->validationLottoRisq($play_config, $draw->getValues(), $lottery->getNextDrawDate(), null, $result_validation->uuid);
                                if (!$result_validation->success()) {
                                    return new ActionResult(false, $result_validation->errorMessage());
                                }
                            }
                            $this->walletService->payGroupedBetsWithWallet($user, $playConfigs[0]->getLottery()->getSingleBetPrice()->multiply(count($playConfigs)));
                            $numPlayConfigs = count($playConfigs);
                        }
                        if ($powerPlay) {
                            $amount = ($lottery->getPowerPlayValue() * $numPlayConfigs) + $lottery->getSingleBetPrice()->multiply($numPlayConfigs)->getAmount();
                        } else {
                            $amount = $lottery->getSingleBetPrice()->multiply($numPlayConfigs)->getAmount();
                        }

                        $dataTransaction = [
                            'lottery_id' => 3,
                            'transactionID' => $uniqueId,
                            'numBets' => count($order->getPlayConfig()),
                            'feeApplied' => $order->getCreditCardCharge()->getIsChargeFee(),
                            'amountWithWallet' => $amount,
                            'walletBefore' => $walletBefore,
                            'amountWithCreditCard' => 0,
                            'playConfigs' => array_map(function ($val) {
                                return $val->getId();
                            }, $order->getPlayConfig()),
                            'discount' => $discount,
                        ];

                        $this->walletService->purchaseTransactionGrouped($user, TransactionType::TICKET_PURCHASE, $dataTransaction);
                        $this->sendEmailPurchase($user, $order->getPlayConfig());

                        return new ActionResult(true, $order);
                    } else {
                        return new ActionResult($result_payment->success(), $order);
                    }
                } else {
                    //error
                }
            } catch (\Exception $e) {

            }
        }
        return new ActionResult(false);
    }

    public function getPlaysFromTemporarilyStorage(User $user, $lottery)
    {
        try {
            /** @var ActionResult $result */
            $result = $this->playStorageStrategy->findByKey($user->getId());
            if ($result->success()) {
                $form_decode = json_decode($result->returnValues());
                $bets = [];
                foreach ($form_decode->play_config as $bet) {
                    $playConfig = new PlayConfig();
                    $playConfig->formToEntity($user, $bet, $bet->euroMillionsLines);
                    $playConfig->setLottery($this->getLottery($lottery));
                    $playConfig->setDiscount(new Discount($bet->frequency, $this->playConfigRepository->retrieveEuromillionsBundlePrice()));
                    $bets[] = $playConfig;
                }

                return new ActionResult(true, $bets);
            } else {
                return new ActionResult(false);
            }
        } catch (\RedisException $r) {
            return new ActionResult(false, $r->getMessage());
        }
    }

    private function getLottery($lottery)
    {
        return $this->lotteryService->getLotteryConfigByName($lottery);
    }

    private function sendEmailPurchase(User $user, $orderLines)
    {
        $emailBaseTemplate = new EmailTemplate();
        $emailTemplate = new PurchaseConfirmationEmailTemplate($emailBaseTemplate, new JackpotDataEmailTemplateStrategy($this->lotteryService));
        if ($orderLines[0]->getFrequency() >= 4) {
            $emailTemplate = new PurchaseSubscriptionConfirmationEmailTemplate($emailBaseTemplate, new JackpotDataEmailTemplateStrategy($this->lotteryService));
//        $emailTemplate->setFrequency($orderLines[0]->getFrequencyPlay());
            $emailTemplate->setDraws($orderLines[0]->getFrequency());
//        $emailTemplate->setJackpot($orderLines[0]->getJackpot());
            $emailTemplate->setStartingDate($orderLines[0]->getStartDrawDate()->format('d-m-Y'));
        }
        $emailTemplate->setLine($orderLines);
        $emailTemplate->setUser($user);

        $this->emailService->sendTransactionalEmail($user, $emailTemplate);
    }
}