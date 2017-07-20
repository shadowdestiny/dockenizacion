<?php

namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;

use EuroMillions\web\emailTemplates\EmailTemplate;
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
use EuroMillions\web\vo\EuroMillionsRegularNumber;
use EuroMillions\web\vo\Order;
use EuroMillions\web\vo\OrderChristmas;
use EuroMillions\web\vo\PlayFormToStorage;
use EuroMillions\shared\vo\results\ActionResult;
use Money\Currency;
use Money\Money;

class PlayService
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
    /** @var  ChristmasService $betService */
    private $christmasService;


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

    public function getPlaysFromGuestUserAndSwitchUser($user_id, $current_user_id)
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
                        $playConfig->setLottery($this->getLottery());
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

                $lottery = $this->lotteryService->getLotteryConfigByName('EuroMillions');
                /** @var User $user */
                $user = $this->userRepository->find(['id' => $user_id]);
                $result_order = $this->cartService->get($user_id);
                $numPlayConfigs = 0;
                if ($result_order->success()) {
                    /** @var Order $order */
                    $order = $result_order->getValues();
                    $discount = $order->getDiscount()->getValue();
                    $order->setIsCheckedWalletBalance($withAccountBalance);
                    $order->addFunds($funds);
                    $order->setAmountWallet($user->getWallet()->getBalance());
                    $draw = $this->lotteryService->getNextDrawByLottery('EuroMillions');
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
                            'transactionID' => $uniqueId,
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
                    $order->setAmountWallet($user->getWallet()->getBalance());

                    $draw = $this->lotteryService->getNextDrawByLottery('Christmas');
                    if ($credit_card != null) {
                        $this->cardPaymentProvider->user($user);
                        $uniqueId = $this->walletService->getUniqueTransactionId();
                        $this->cardPaymentProvider->idTransaction = $uniqueId;
                        $result_payment = $this->walletService->payWithCreditCardChristmas($this->cardPaymentProvider, $credit_card, $user, $uniqueId, $order, $isWallet);
                    } else {
                        $result_payment = new ActionResult(true, $order);
                    }

                    if (count($order->getPlayConfig()) > 0 && $result_payment->success()) {
                        //EMTD be careful now, set explicity lottery, but it should come inform on playconfig entity
                        /** @var ChristmasTickets $play_config */
                        foreach ($order->getPlayConfig() as $play_config) {
                            $playConfigChristmas = new PlayConfig();
                            $playConfigChristmas->setActive(1);
                            $playConfigChristmas->setFrequency(1);
                            $playConfigChristmas->setLastDrawDate($lottery->getNextDrawDate());
                            $playConfigChristmas->setStartDrawDate($lottery->getNextDrawDate());
                            $playConfigChristmas->setUser($user);
                            $playConfigChristmas->setLine(new EuroMillionsLine([new EuroMillionsRegularNumber(str_split($play_config->getNumber()))], [new EuroMillionsLuckyNumber($play_config->getNumSeries(), $play_config->getNumFractions())]));

                            $playConfigChristmas->setLottery($lottery->getId());
                            $this->playConfigRepository->add($playConfigChristmas);
                            $this->entityManager->flush($playConfigChristmas);
                        }
                    }
                    if ($result_payment->success()) {
                        $walletBefore = $user->getWallet();
                        $config = $di->get('config');

                        if ($config->application->send_single_validations) {
                            foreach ($order->getPlayConfig() as $play_config) {
                                $result_validation = $this->christmasService->validationChristmas($play_config, $draw->getValues(), $lottery->getNextDrawDate());
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
                            'transactionID' => $uniqueId,
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


    public function getPlaysFromTemporarilyStorage(User $user)
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
                    $playConfig->setLottery($this->getLottery());
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



    //Enric, por favor, refactoriza toda la clase en partes más pequeñas. Evitando las dependencias que se utlizan una vez en la clase.

    /**
     * @return BundlePlayCollectionDTO
     */
    public function retrieveEuromillionsBundlePriceDTO()
    {
        $bundlePlayCollectionDTO = new BundlePlayCollectionDTO($this->playConfigRepository->retrieveEuromillionsBundlePrice(), $this->getLottery()->getSingleBetPrice());
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

    //EMTD workaround, now only once lottery we have. In the future should pass lottery as param
    private function getLottery()
    {
        return $this->lotteryService->getLotteryConfigByName('EuroMillions');
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