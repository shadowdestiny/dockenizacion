<?php

namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;

use EuroMillions\shared\enums\PurchaseConfirmationEnum;
use EuroMillions\shared\vo\RedisOrderKey;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\ErrorEmailTemplate;
use EuroMillions\web\emailTemplates\PowerBallPurchaseConfirmationEmailTemplate;
use EuroMillions\web\emailTemplates\PowerBallPurchaseSubscriptionConfirmationEmailTemplate;
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
use EuroMillions\shared\components\PaymentsCollection;
use EuroMillions\web\services\card_payment_providers\FakeCardPaymentProvider;
use EuroMillions\web\services\card_payment_providers\PayXpertCardPaymentStrategy;
use EuroMillions\web\services\card_payment_providers\shared\dto\NormalBodyResponse;
use EuroMillions\web\services\card_payment_providers\shared\dto\PaymentBodyResponse;
use EuroMillions\web\services\card_payment_providers\shared\PaymentRedirectContext;
use EuroMillions\web\services\card_payment_providers\widecard\WideCardConfig;
use EuroMillions\web\services\card_payment_providers\WideCardPaymentProvider;
use EuroMillions\web\services\email_templates_strategies\ErrorDataEmailTemplateStrategy;
use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\services\factories\LotteryValidatorsFactory;
use EuroMillions\web\services\notification_mediator\Colleague;
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
use EuroMillions\web\vo\TransactionId;
use Exception;
use Money\Currency;
use Money\Money;

class PlayService extends Colleague
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
    public function __construct(
        EntityManager $entityManager,
        LotteryService $lotteryService,
        IPlayStorageStrategy $playStorageStrategy,
        IPlayStorageStrategy $orderStorageStrategy,
        CartService $cartService,
        WalletService $walletService,
        ICardPaymentProvider $payXpertCardPaymentStrategy,
        BetService $betService,
        EmailService $emailService
    ) {
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
            $lottery = $this->getLottery($lottery);
            /** @var ActionResult $result_find_playstorage */
            $result_find_playstorage = $this->playStorageStrategy->findByKey(RedisOrderKey::create($user_id, $lottery->getId())->key());
            if ($result_find_playstorage->success()) {
                $this->playStorageStrategy->save($result_find_playstorage->returnValues(), RedisOrderKey::create($current_user_id, $lottery->getId())->key());
                $result_save_playstorage = $this->playStorageStrategy->findByKey(RedisOrderKey::create($current_user_id, $lottery->getId())->key());
                if ($result_save_playstorage->success()) {
                    $form_decode = json_decode($result_find_playstorage->getValues());
                    $bets = [];
                    foreach ($form_decode->play_config as $bet) {
                        $playConfig = new PlayConfig();
                        $playConfig->formToEntity($user, $bet, $bet->euroMillionsLines);
                        $playConfig->setLottery($lottery);
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
     * @param Money|null $funds
     * @param CreditCard|null $credit_card
     * @param bool $withAccountBalance
     * @param null $isWallet
     * @param string $lotteryName
     * @param PaymentsCollection $paymentsCollection
     * @return ActionResult
     */
    public function playWithQueue($user_id, Money $funds = null, CreditCard $credit_card = null, $withAccountBalance = false, $isWallet = null, $lotteryName = "PowerBall", PaymentsCollection $paymentsCollection)
    {
        try {
            /** @var Lottery $lottery */
            $lottery = $this->lotteryService->getLotteryConfigByName($lotteryName);
            /** @var User $user */
            $user = $this->userRepository->find(['id' => $user_id]);
            $result_order = $this->cartService->get($user_id, $lottery->getName(), $isWallet);
            $paymentBodyResponse = null;
            if ($result_order->success()) {
                /** @var Order $order */
                $order = $result_order->getValues();
                if (is_null($credit_card) && $withAccountBalance) {
                    if ($order->totalOriginal()->getAmount() > $user->getBalance()->getAmount()) {
                        return new ActionResult(false);
                    }
                }
                //EMTD use OrderFactory
                $order->setIsCheckedWalletBalance($withAccountBalance);
                $order->setLottery($lottery);
                $order->setAmountWallet($user->getWallet()->getBalance());
                $uniqueId = TransactionId::create(); //TODO: Remove this UniqueID
                if ($credit_card != null) {
                    $this->cardPaymentProvider = $paymentsCollection->getIterator()->current()->get();
                    $result_payment = $this->walletService->onlyPay($this->cardPaymentProvider, $credit_card, $user, $uniqueId, $order, $isWallet);
                    /** @var PaymentBodyResponse $paymentBodyResponse */
                    $paymentBodyResponse = $result_payment->returnValues();
                } else {
                    if ($order->getHasSubscription()) {
                        $this->walletService->createSubscriptionTransaction($user, $uniqueId, $order);
                    }
                    $paymentBodyResponse = new NormalBodyResponse(true);
                    $this->cardPaymentProvider = new FakeCardPaymentProvider();
                    //$result_payment = new ActionResult(true, $order);
                }
                return (new PaymentRedirectContext($this->cardPaymentProvider,$order->getLottery()->getName()))->execute($paymentBodyResponse);
                //return new ActionResult($result_payment->success(), $order);
            }
            //return new ActionResult(false);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param $user_id
     * @param Money $funds
     * @param CreditCard $credit_card
     * @param bool $withAccountBalance
     * @param null $isWallet
     * @param ICardPaymentProvider $paymentProvider
     * @return ActionResult
     */
    public function play($user_id, Money $funds = null, CreditCard $credit_card = null, $withAccountBalance = false, $isWallet = null, ICardPaymentProvider $paymentProvider = null)
    {
        if ($paymentProvider !== null) {
            $this->cardPaymentProvider = $paymentProvider;
        }

        if ($user_id) {
            try {
                $di = \Phalcon\Di::getDefault();

                $lottery = $this->lotteryService->getLotteryConfigByName('EuroMillions');
                /** @var User $user */
                $user = $this->userRepository->find(['id' => $user_id]);
                $result_order = $this->cartService->get($user_id, $lottery->getName(), $isWallet);
                $numPlayConfigs = 0;
                $paymentBodyResponse=null;
                if ($result_order->success()) {
                    /** @var Order $order */
                    $order = $result_order->getValues();
                    if (is_null($credit_card) && $withAccountBalance) {
                        if ($order->totalOriginal()->getAmount() > $user->getBalance()->getAmount()) {
                            return new ActionResult(false);
                        }
                    }

                    $discount = $order->getDiscount()->getValue();
                    $order->setIsCheckedWalletBalance($withAccountBalance);
                    $order->setLottery($lottery);
                    $order->addFunds($funds);
                    $order->setAmountWallet($user->getWallet()->getBalance());
                    $draw = $this->lotteryService->getNextDrawByLottery('EuroMillions');
                    if ($credit_card != null) {
                        $result_payment = $this->walletService->payWithCreditCard($this->cardPaymentProvider, $credit_card, $user, $order, $isWallet);
                        /** @var PaymentBodyResponse $paymentBodyResponse */
                        $paymentBodyResponse = $result_payment->returnValues();
                    } else {
                        if ($order->getHasSubscription()) {
                            $this->walletService->createSubscriptionTransaction($user, $order->getTransactionId(), $order);
                        }
                        $result_payment = new ActionResult(true, $order);
                        $paymentBodyResponse = new NormalBodyResponse($result_payment->success());
                        $this->cardPaymentProvider = new FakeCardPaymentProvider();
                    }
                    if (count($order->getPlayConfig()) > 0 && $result_payment->success()) {
                        //EMTD be careful now, set explicity lottery, but it should come inform on playconfig entity
                        /** @var PlayConfig $play_config */
                        foreach ($order->getPlayConfig() as $play_config) {
                            $play_config->setLottery($lottery);
                            $play_config->setDiscount($order->getDiscount());
                            $this->playConfigRepository->add($play_config);
                            $this->entityManager->flush($play_config);
                        }
                    }
                    $orderIsToNextDraw = $order->isNextDraw($draw->getValues()->getDrawDate());
                    if ($result_payment->success() && $orderIsToNextDraw) {
                        $walletBefore = $user->getWallet();
                        $config = $di->get('config');
                        if ($config->application->send_single_validations) {
                            foreach ($order->getPlayConfig() as $play_config) {
                                $result_validation = $this->betService->validation($play_config, $draw->getValues(), $lottery->getNextDrawDate());

                                if (!$result_validation->success()) {
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
                                $result_validation = $this->betService->groupingValidation($playConfigsSplit, $draw->getValues(), $lottery->getNextDrawDate());
                                if (!$result_validation->success()) {
                                    return new ActionResult(false, $result_validation->errorMessage());
                                }
                            }
                            $this->walletService->payGroupedBetsWithWallet($user, $playConfigs[0]->getLottery()->getSingleBetPrice()->multiply(count($playConfigs)));
                            $numPlayConfigs = count($playConfigs);
                        }

                        $dataTransaction = [
                            'lottery_id' => 1,
                            'transactionID' => $order->getTransactionId(),
                            'numBets' => count($order->getPlayConfig()),
                            'feeApplied' => $order->getCreditCardCharge()->getIsChargeFee(),
                            'amountWithWallet' => $lottery->getSingleBetPrice()->multiply($numPlayConfigs)->getAmount(),
                            'walletBefore' => $walletBefore,
                            'amountWithCreditCard' => 0,
                            'playConfigs' => array_map(function ($val) {
                                return $val->getId();
                            }, $order->getPlayConfig()),
                            'discount' => $discount,
                        ];
                        $this->walletService->purchaseTransactionGrouped($user, TransactionType::TICKET_PURCHASE, $dataTransaction);
                        $this->sendEmailPurchase($user, $order->getPlayConfig());
                        (new PaymentRedirectContext($this->cardPaymentProvider,$order->getLottery()->getName()))->execute($paymentBodyResponse);
                    } else {
                        return new ActionResult($result_payment->success(), $order);
                    }
                } else {
                    //error
                }
            } catch (\Exception $e) {
            }
        }
    }


    public function playWithMoneyMatrix($lotteryName, $transactionID, $userID, $withWallet, $amount)
    {
        try {
            $di = \Phalcon\Di::getDefault();
            /** @var Lottery $lottery */
            $lottery = $this->lotteryService->getLotteryConfigByName($lotteryName);
            /** @var User $user */
            $user = $this->userRepository->find(['id' => $userID]);
            $result_order = $this->cartService->get($userID, $lotteryName);
            /** @var Order $order */
            $order = $result_order->getValues();
            $order->setIsCheckedWalletBalance($withWallet);
            //$order->addFunds(new Money((int) $amount, new Currency('EUR')));
            $order->setLottery($lottery);
            $order->addFunds(null);
            $order->setAmountWallet($user->getWallet()->getBalance());
            $draw = $this->lotteryService->getNextDrawByLottery($lotteryName);
            $result_payment = new ActionResult(true, $order);
            if (count($order->getPlayConfig()) > 0 && $result_payment->success()) {
                /** @var PlayConfig $play_config */
                foreach ($order->getPlayConfig() as $play_config) {
                    $play_config->setLottery($lottery);
                    $this->playConfigRepository->add($play_config);
                    $this->entityManager->flush($play_config);
                }
            }
            $this->walletService->payWithMoneyMatrix($user, $transactionID, $order, $withWallet, $amount);
            $orderIsToNextDraw = $order->isNextDraw($draw->getValues()->getDrawDate());
            if ($result_payment->success() && $orderIsToNextDraw) {
                $walletBefore = $user->getWallet();
                $config = $di->get('config');
                if ($config->application->send_single_validations) {
                    foreach ($order->getPlayConfig() as $play_config) {
                        $result_validation = $this->validatorResult($lottery, $play_config, $draw, $order);
                        if (!$result_validation->success()) {
                            return new ActionResult(false, $result_validation->errorMessage());
                        }
                        if ($order->getHasSubscription()) {
                            $this->walletService->createSubscriptionTransaction($user, $transactionID, $order);
                            if ($withWallet) {
                                $order->setAmountWallet($user->getWallet()->getBalance());
                                $this->walletService->payWithSubscription($user, $play_config, null, $order);
                                $this->walletService->paySubscriptionWithWalletAndCreditCard($user, $play_config, null, $order);
                            } else {
                                $this->walletService->payWithSubscription($user, $play_config, null, $order);
                            }
                        } else {
                            $this->walletService->payWithWallet($user, $play_config, null, $order);
                        }
                    }
                    $numPlayConfigs = count($order->getPlayConfig());
                } else {
                    $playConfigs = $order->getPlayConfig();
                    foreach (array_chunk($playConfigs, self::NUM_BETS_PER_REQUEST) as $playConfigsSplit) {
                        $result_validation = $this->betService->groupingValidation($playConfigsSplit, $draw->getValues(), $lottery->getNextDrawDate());
                        if (!$result_validation->success()) {
                            return new ActionResult(false, $result_validation->errorMessage());
                        }
                    }
                    $this->walletService->payGroupedBetsWithWallet($user, $playConfigs[0]->getLottery()->getSingleBetPrice()->multiply(count($playConfigs)));
                    $numPlayConfigs = count($playConfigs);
                }

                $dataTransaction = [
                    'lottery_id' => $lottery->getId(),
                    'transactionID' => $transactionID,
                    'numBets' => count($order->getPlayConfig()),
                    'feeApplied' => $order->getCreditCardCharge()->getIsChargeFee(),
                    'amountWithWallet' => $lottery->getSingleBetPrice()->multiply($numPlayConfigs)->getAmount(),
                    'walletBefore' => $walletBefore,
                    'amountWithCreditCard' => 0,
                    'playConfigs' => array_map(function ($val) {
                        return $val->getId();
                    }, $order->getPlayConfig()),
                    'discount' => $order->getDiscount()->getValue()
                ];
                $this->walletService->purchaseTransactionGrouped($user, TransactionType::TICKET_PURCHASE, $dataTransaction);
                $this->sendEmailPurchase($user, $order->getPlayConfig());
                return new ActionResult(true, $order);
            } else {
                return new ActionResult($result_payment->success(), $order);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param $user_id
     * @param Money $funds
     * @param CreditCard $credit_card
     * @param bool $withAccountBalance
     * @return ActionResult
     */
    public function playChristmas($user_id, Money $funds = null, CreditCard $credit_card = null, $withAccountBalance = false, $isWallet = null, $resultOrder)
    {
        if ($user_id) {
            try {
                $di = \Phalcon\Di::getDefault();
                /** @var Lottery $lottery */
                $lottery = $this->lotteryService->getLotteryConfigByName('Christmas');
                /** @var User $user */
                $user = $this->userRepository->find(['id' => $user_id]);

                $numPlayConfigs = 0;
                if ($resultOrder) {
                    /** @var OrderChristmas $order */
                    $order = new OrderChristmas($resultOrder, $this->lotteryService->getSingleBetPriceByLottery('Christmas'), new Money(0, new Currency('EUR')), new Money(0, new Currency('EUR')), new Discount(0, 0));
                    $order->setIsCheckedWalletBalance($withAccountBalance);
                    $order->addFunds($funds);
                    $order->setLottery($lottery);
                    $order->setAmountWallet($user->getWallet()->getBalance());
                    $draw = $this->lotteryService->getNextDrawByLottery('Christmas');
                    //Workaround temporal
                    $config = $di->get('config')['wirecard'];
                    $this->cardPaymentProvider = new WideCardPaymentProvider(
                        new WideCardConfig(
                        $config->endpoint,
                        $config->api_key,
                        $config->weight,
                        $config->countries
                    )
                    );
                    if ($credit_card != null) {
                        $this->cardPaymentProvider->user($user);
                        $uniqueId = $this->walletService->getUniqueTransactionId();
                        $this->cardPaymentProvider->idTransaction = $uniqueId;
                        $result_payment = $this->walletService->payWithCreditCardChristmas($this->cardPaymentProvider, $credit_card, $user, $uniqueId, $order, $isWallet);
                    } else {
                        $uniqueId = null;
                        $result_payment = new ActionResult(true, $order);
                    }
                    $allPlayConfigsChristmas = [];
                    if (count($order->getPlayConfig()) > 0 && $result_payment->success()) {
                        $discountNumFraction = 0;
                        //EMTD be careful now, set explicity lottery, but it should come inform on playconfig entity
                        /** @var ChristmasTickets $play_config */
                        $numbers = [];
                        $repeated = false;
                        foreach ($order->getPlayConfig() as $play_config) {
                            $playConfigChristmas = new PlayConfig();
                            $playConfigChristmas->setId(1);
                            $playConfigChristmas->setActive(true);
                            $playConfigChristmas->setFrequency(1);
                            $playConfigChristmas->setLastDrawDate($lottery->getNextDrawDate());
                            $playConfigChristmas->setStartDrawDate($lottery->getNextDrawDate());
                            $playConfigChristmas->setUser($user);
                            $playConfigChristmas->setDiscount(new Discount(0, []));
                            $numberLine = [];
                            $luckyLine = [];
                            $numberDiscount = '';
                            foreach (str_split($play_config->getNumber()) as $number) {
                                $numberLine[] = new EuroMillionsRegularNumber(intval($number), $lottery->getId());
                                $numberDiscount = $numberDiscount . intval($number);
                            }
                            if (in_array($numberDiscount, $numbers)) {
                                $discountNumFraction++;
                            } else {
                                $discountNumFraction = 0;
                            }
                            $numbers[] = $numberDiscount;
                            $luckyLine[] = new EuroMillionsLuckyNumber(intval($play_config->getSerieInit()), $lottery->getId());

                            if ($numberDiscount == 93754 && $play_config->getNumFractions() == 9 && $repeated == false) {
                                $luckyLine[] = new EuroMillionsLuckyNumber(10, $lottery->getId());
                                $repeated = true;
                            } else {
                                $luckyLine[] = new EuroMillionsLuckyNumber(intval($play_config->getNumFractions()) - $discountNumFraction, $lottery->getId());
                            }

                            $playConfigChristmas->setLine(new EuroMillionsLine($numberLine, $luckyLine, $lottery->getId()));

                            $playConfigChristmas->setLottery($lottery);
                            $this->playConfigRepository->add($playConfigChristmas);
                            $this->entityManager->flush($playConfigChristmas);
                            $allPlayConfigsChristmas[] = $playConfigChristmas;
                        }
                    }

                    if ($result_payment->success()) {
                        $walletBefore = $user->getWallet();
                        /* @var PlayConfig $playConfigChristmas */
                        foreach ($allPlayConfigsChristmas as $playConfigChristmas) {
                            $result_validation = $this->betService->validationChristmas($playConfigChristmas, $draw->getValues(), $lottery->getNextDrawDate());

                            if (!$result_validation->success()) {
                                return new ActionResult(false, $result_validation->errorMessage());
                            }
                            $this->walletService->payWithWallet($user, $playConfigChristmas);
                            $this->playConfigRepository->substractNumFractionsToChristmasTicket($playConfigChristmas->getLine()->getRegularNumbers());
                        }

                        $numPlayConfigs = count($allPlayConfigsChristmas);
                        $dataTransaction = [
                            'lottery_id' => 2,
                            'transactionID' => $uniqueId,
                            'numBets' => count($allPlayConfigsChristmas),
                            'feeApplied' => $order->getCreditCardCharge()->getIsChargeFee(),
                            'amountWithWallet' => $lottery->getSingleBetPrice()->multiply($numPlayConfigs)->getAmount(),
                            'walletBefore' => $walletBefore,
                            'amountWithCreditCard' => 0,
                            'playConfigs' => array_map(function ($val) {
                                return $val->getId();
                            }, $allPlayConfigsChristmas),
                            'discount' => 0,
                        ];

                        $this->walletService->purchaseTransactionGrouped($user, TransactionType::TICKET_PURCHASE, $dataTransaction);

                        $this->sendEmailPurchaseChristmas($user, $order->getPlayConfig());
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
            $result = $this->playStorageStrategy->findByKey(RedisOrderKey::create($user->getId(), $this->getLottery($lottery)->getId())->key());
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

    public function getChristmasPlaysFromTemporarilyStorage(User $user)
    {
        try {
            /** @var ActionResult $result */
            $result = $this->playStorageStrategy->findByChristmasKey($user->getId());

            if ($result->success()) {
                return new ActionResult(true, $result->returnValues());
            } else {
                return new ActionResult(false);
            }
        } catch (\RedisException $r) {
            return new ActionResult(false, $r->getMessage());
        }
    }

    public function savePlayFromJson($json, $userId)
    {
        $result = $this->playStorageStrategy->save($json, $userId);
        if ($result->success()) {
            return new ActionResult(true);
        } else {
            return new ActionResult(false);
        }
    }

    public function saveChristmasPlay($christmasTickets, $userId)
    {
        $result = $this->playStorageStrategy->saveChristmas($christmasTickets, $userId);
        if ($result->success()) {
            return new ActionResult(true);
        } else {
            return new ActionResult(false);
        }
    }


    public function temporarilyStorePlay(PlayFormToStorage $playForm, $userId)
    {
        $result = $this->playStorageStrategy->saveAll($playForm, $userId);
        if ($result->success()) {
            return new ActionResult(true);
        } else {
            return new ActionResult(false);
        }
    }

    public function getPlaysConfigToBet(\DateTime $date)
    {
        $result = $this->playConfigRepository->getPlayConfigsByDrawDayAndDate($date);
        if (!empty($result)) {
            return new ActionResult(true, $result);
        } else {
            return new ActionResult(false);
        }
    }

    public function getPlayConfigWithLongEnded(\DateTime $date)
    {
        $result = $this->playConfigRepository->getPlayConfigsLongEnded($date);
        if (!empty($result)) {
            return new ActionResult(true, $result);
        } else {
            return new ActionResult(false);
        }
    }

    public function removeStorePlay($user_id)
    {
        if (null != $user_id) {
            $this->playStorageStrategy->delete($user_id);
        }
    }

    public function removeStoreOrder($user_id)
    {
        if (null != $user_id) {
            $this->orderStorageStrategy->delete($user_id);
        }
    }

    /**
     * @return BundlePlayCollectionDTO
     */
    public function retrieveEuromillionsBundlePriceDTO($lottery)
    {
        $bundlePlayCollectionDTO = new BundlePlayCollectionDTO($this->playConfigRepository->retrieveEuromillionsBundlePrice(), $this->getLottery($lottery)->getSingleBetPrice());
        $di = \Phalcon\Di::getDefault();
        /** @var DomainServiceFactory $domainServiceFactory */
        $domainServiceFactory = $di->get('domainServiceFactory');
        $current_currency = $domainServiceFactory->getUserPreferencesService()->getCurrency();
        if ($domainServiceFactory->getAuthService()->isLogged()) {
            /** @var User $user */
            $user = $this->userRepository->find($domainServiceFactory->getAuthService()->getCurrentUser()->getId());
            $userCurrency = $user->getUserCurrency();
            $domainServiceFactory->getUserPreferencesService()->setCurrency($userCurrency);
            $current_currency = $userCurrency;
        }
        /** @var BundlePlayDTO $bundlePlayDto */
        foreach ($bundlePlayCollectionDTO->bundlePlayDTO as $bundlePlayDto) {
            $moneyConverted = $domainServiceFactory->getCurrencyConversionService()->convert($bundlePlayDto->singleBetPriceWithDiscount, $current_currency);
            $bundlePlayDto->singleBetPriceWithDiscount = $moneyConverted;
        }
        return $bundlePlayCollectionDTO;
    }

    /**
     * @return BundlePlayCollectionDTO
     */
    public function retrievePowerBallBundlePriceDTO($lottery)
    {
        $bundlePlayCollectionDTO = new BundlePlayCollectionDTO($this->playConfigRepository->retrieveEuroMillionsBundlePrice(), $this->getLottery($lottery)->getSingleBetPrice());
        $di = \Phalcon\Di::getDefault();
        /** @var DomainServiceFactory $domainServiceFactory */
        $domainServiceFactory = $di->get('domainServiceFactory');
        $current_currency = $domainServiceFactory->getUserPreferencesService()->getCurrency();
        if ($domainServiceFactory->getAuthService()->isLogged()) {
            /** @var User $user */
            $user = $this->userRepository->find($domainServiceFactory->getAuthService()->getCurrentUser()->getId());
            $userCurrency = $user->getUserCurrency();
            $domainServiceFactory->getUserPreferencesService()->setCurrency($userCurrency);
            $current_currency = $userCurrency;
        }
        /** @var BundlePlayDTO $bundlePlayDto */
        foreach ($bundlePlayCollectionDTO->bundlePlayDTO as $bundlePlayDto) {
            $moneyConverted = $domainServiceFactory->getCurrencyConversionService()->convert($bundlePlayDto->singleBetPriceWithDiscount, $current_currency);
            $bundlePlayDto->singleBetPriceWithDiscount = $moneyConverted;
        }
        return $bundlePlayCollectionDTO;
    }

    public function getPowerPlay()
    {
        return $this->playConfigRepository->powerPlayPrice();
    }

    public function getBundleDataAsArray()
    {
        return $this->playConfigRepository->retrieveEuromillionsBundlePrice();
    }

    /**
     * @param $lotteryId
     *
     * @return array
     */
    public function getAllSubscriptionsActivesByLotteryId($lotteryId)
    {
        return $this->playConfigRepository->getAllSubscriptionsActivesByLotteryId($lotteryId);
    }

    /**
     * @param $lotteryId
     *
     * @return array
     */
    public function getAllSubscriptionsPlayedByLotteryId($lotteryId)
    {
        return $this->playConfigRepository->getAllSubscriptionsPlayedByLotteryId($lotteryId, $this->lotteryService->getNextDateDrawByLottery('Euromillions'));
    }

    private function getLottery($lottery)
    {
        return $this->lotteryService->getLotteryConfigByName($lottery);
    }

    public function sendEmailPurchase(User $user, $orderLines)
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

    public function sendErrorEmail(User $user, Order $order, $dateOrder)
    {
        $emailBaseTemplate = new EmailTemplate();
        $emailTemplate = new ErrorEmailTemplate($emailBaseTemplate, new ErrorDataEmailTemplateStrategy($user, $order, $dateOrder));
        $this->emailService->sendTransactionalEmail($user, $emailTemplate);
    }

    public function validatorResult(Lottery $lottery, $play_config, ActionResult $draw, Order $order)
    {
        //TODO: workaround to avoid calls to lottorisq
        static $calls = 0;
        static $response = null;
        $calls++;
        try {
            $lotteryValidator = LotteryValidatorsFactory::create($lottery->getName());
            if ($lottery->getName() == 'EuroMillions') {
                return $this->betService->validation($play_config, $draw->getValues(), $lottery->getNextDrawDate(), null, $lotteryValidator);
            }
            if ($calls == 1) {
                $response = json_decode($lotteryValidator->book(json_encode($order->getPlayConfig()))->body);
                return new ActionResult(true, $response);
            }
        } catch (\Exception $e) {
            return new ActionResult(false);
        }

        return new ActionResult(true, $response);
    }

    public function persistBetDistinctEuroMillions($play_config, ActionResult $draw, Order $order, $resultValidation)
    {
        if ($order->getLottery()->getName() !== 'EuroMillions') {
            return $this->betService->validationLottery($play_config, $draw->getValues(), $order->getLottery()->getNextDrawDate(), null, $resultValidation->uuid);
        }
        return new ActionResult(true);
    }


    public function retrieveEuromillionsBundlePrice()
    {
        return $this->playConfigRepository->retrieveEuromillionsBundlePrice();
    }

    private function sendEmailPurchaseChristmas(User $user, $orderLines)
    {
        $emailBaseTemplate = new EmailTemplate();
        $emailTemplate = new PurchaseConfirmationChristmasEmailTemplate($emailBaseTemplate, new JackpotDataEmailTemplateStrategy($this->lotteryService));
        $emailTemplate->setLine($orderLines);
        $emailTemplate->setUser($user);

        $this->emailService->sendTransactionalEmail($user, $emailTemplate);
    }

    public function sendEmailPurchaseQueue(User $user, $orderLines, $lotteryName)
    {
        $template = (new PurchaseConfirmationEnum())->findTemplatePathByLotteryName($lotteryName);
        $emailBaseTemplate = new EmailTemplate();
        $emailTemplate = new $template($emailBaseTemplate, new JackpotDataEmailTemplateStrategy($this->lotteryService));
        if ($orderLines[0]->getFrequency() >= 4) {
            $template = (new PurchaseConfirmationEnum())->findTemplatePathByLotteryName($lotteryName, true);
            $emailTemplate = new $template($emailBaseTemplate, new JackpotDataEmailTemplateStrategy($this->lotteryService));
            $emailTemplate->setDraws($orderLines[0]->getFrequency());
            $emailTemplate->setStartingDate($orderLines[0]->getStartDrawDate()->format('d-m-Y'));
        }
        $emailTemplate->setLine($orderLines);
        $emailTemplate->setUser($user);

        $this->emailService->sendTransactionalEmail($user, $emailTemplate);
    }
}
