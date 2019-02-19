<?php

namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\shared\interfaces\IResult;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\CreditCardCharge;
use EuroMillions\web\vo\dto\WalletDTO;
use EuroMillions\web\vo\enum\TransactionType;
use EuroMillions\web\vo\Order;
use EuroMillions\web\vo\OrderChristmas;
use Money\Currency;
use Money\Money;

class WalletService
{
    private $entityManager;
    private $currencyConversionService;
    private $transactionService;
    /** @var  LotteryService */
    private $lotteryService;

    public function __construct(EntityManager $entityManager, CurrencyConversionService $currencyConversionService, TransactionService $transactionService)
    {
        $this->entityManager = $entityManager;
        $this->currencyConversionService = $currencyConversionService;
        $this->transactionService = $transactionService;
    }

    /**
     * @param ICardPaymentProvider $provider
     * @param CreditCard $card
     * @param User $user
     * @param CreditCardCharge $creditCardCharge
     * @return IResult
     */
    public function rechargeWithCreditCard(ICardPaymentProvider $provider,
                                           CreditCard $card,
                                           User $user,
                                           CreditCardCharge $creditCardCharge)
    {
        $provider->user($user);
        $uniqueId = $this->getUniqueTransactionId();
        $provider->idTransaction = $uniqueId;
        $payment_result = $this->pay($provider, $card, $creditCardCharge);
        if ($payment_result->success()) {
            $walletBefore = $user->getWallet();
            $user->reChargeWallet($creditCardCharge->getNetAmount());
            try {
                $this->entityManager->persist($user);
                $this->entityManager->flush($user);
                $dataTransaction = $this->buildDepositTransactionData($user, $creditCardCharge, $uniqueId, $walletBefore);
                $this->transactionService->storeTransaction(TransactionType::DEPOSIT, $dataTransaction);
            } catch (\Exception $e) {
                //EMTD Log and warn the admin
            }
        }
        return $payment_result;
    }

    /**
     * @param ICardPaymentProvider $provider
     * @param CreditCard $card
     * @param User $user
     * @param CreditCardCharge $creditCardCharge
     * @return IResult
     */
    public function payWithCreditCard(ICardPaymentProvider $provider,
                                      CreditCard $card,
                                      User $user,
                                      $uniqueID = null,
                                      Order $order,
                                      $isWallet = null)
    {

        $creditCardCharge = $order->getCreditCardCharge();

        $payment_result = $this->pay($provider, $card, $creditCardCharge);
        if ($payment_result->success()) {
            try {
                $walletBefore = $user->getWallet();
                if (!$order->getHasSubscription()) {
                    $user->reChargeWallet($creditCardCharge->getNetAmount());
                    $dataTransaction = $this->buildDepositTransactionData($user, $creditCardCharge, $uniqueID, $walletBefore,$order);
                    $this->transactionService->storeTransaction(TransactionType::DEPOSIT, $dataTransaction);
                } else {
                    $user->reChargeSubscriptionWallet($creditCardCharge->getNetAmount());
                    if ($isWallet) {
                        $user->removeSubscriptionWithWallet($creditCardCharge->getNetAmount());
                    }
                    $dataTransaction = $this->buildDepositTransactionData($user, $creditCardCharge, $uniqueID, $walletBefore,$order);
                    $this->transactionService->storeTransaction(TransactionType::SUBSCRIPTION_PURCHASE, $dataTransaction);
                }
                $this->entityManager->persist($user);
                $this->entityManager->flush($user);
            } catch (\Exception $e) {

            }
        }
        return $payment_result;
    }

    public function payOrder(User $user, Order $order)
    {
        if($order->getHasSubscription())
        {
            $user->reChargeSubscriptionWallet($order->getCreditCardCharge()->getNetAmount());
            if($order->isIsCheckedWalletBalance())
            {
                $toSubstract = $order->getUnitPriceSubscription()->multiply($order->totalPlayConfigs());
                $user->removeWalletToSubscription($toSubstract);
            }
        } else {
            $user->reChargeWallet($order->getCreditCardCharge()->getNetAmount());
        }
        try
        {
            $this->entityManager->persist($user);
            $this->entityManager->flush($user);
            return $user;
        } catch (\Exception $e)
        {
        }
    }



    public function extract(User $user, Order $order)
    {
        if($order->getHasSubscription())
        {
            $user->removeSubscriptionWallet($order->getUnitPrice());
        } else
        {
            $user->pay($order->getUnitPrice());
        }
        try
        {
            $this->entityManager->flush($user);
        } catch(\Exception $e)
        {

        }

    }


    public function payWithMoneyMatrix(User $user, $transactionID, Order $order, $isWallet,$amount)
    {

        $creditCardCharge = $order->getCreditCardCharge();
        try {
            $walletBefore = $user->getWallet();
            if (!$order->getHasSubscription()) {
                $user->reChargeWallet($amount);
                $dataTransaction = $this->buildDepositTransactionData($user, $creditCardCharge, $transactionID, $walletBefore,$order);
                $this->transactionService->storeTransaction(TransactionType::DEPOSIT, $dataTransaction);
            } else {
                $user->reChargeSubscriptionWallet($amount);
                if ($isWallet) {
                    $user->removeSubscriptionWithWallet($amount);
                }
                $dataTransaction = $this->buildDepositTransactionData($user, $creditCardCharge, $transactionID, $walletBefore,$order);
                $this->transactionService->storeTransaction(TransactionType::SUBSCRIPTION_PURCHASE, $dataTransaction);
            }
            $this->entityManager->persist($user);
            $this->entityManager->flush($user);
        } catch (\Exception $e) {
        }
    }

    /**
     * @param ICardPaymentProvider $provider
     * @param CreditCard $card
     * @param User $user
     * @param CreditCardCharge $creditCardCharge
     * @return IResult
     */
    public function payWithCreditCardChristmas(ICardPaymentProvider $provider,
                                               CreditCard $card,
                                               User $user,
                                               $uniqueID = null,
                                               OrderChristmas $order,
                                               $isWallet = null)
    {

        $creditCardCharge = $order->getCreditCardCharge();
        $payment_result = $this->pay($provider, $card, $creditCardCharge);
        if ($payment_result->success()) {
            try {
                $walletBefore = $user->getWallet();

                $user->reChargeWallet($creditCardCharge->getNetAmount());
                $dataTransaction = $this->buildDepositTransactionData($user, $creditCardCharge, $uniqueID, $walletBefore,$order);
                $this->transactionService->storeTransaction(TransactionType::DEPOSIT, $dataTransaction);

                $this->entityManager->persist($user);
                $this->entityManager->flush($user);
            } catch (\Exception $e) {

            }
        }
        return $payment_result;
    }


    public function pay(ICardPaymentProvider $provider, CreditCard $card, CreditCardCharge $creditCardCharge)
    {
        try {
            $amount = $creditCardCharge->getFinalAmount();
            return $provider->charge($amount, $card);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function payFromEmpay(User $user, Money $amount)
    {
        $walletBefore = $user->getWallet();
        $user->reChargeWallet($amount);
        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush($user);
            $dataTransaction = [
                'lottery_id' => 1,
                'numBets' => count($user->getPlayConfig()),
                'feeApplied' => $amount->getAmount() > 1200 ? 0 : 1,
                'amountWithWallet' => 0,
                'amountWithCreditCard' => $amount,
                'user' => $user,
                'walletBefore' => $walletBefore,
                'walletAfter' => $user->getWallet(),
                'now' => new \DateTime()
            ];
            $this->transactionService->storeTransaction(TransactionType::DEPOSIT, $dataTransaction);
        } catch (\Exception $e) {
            return new ActionResult(false);
        }
        return new ActionResult(true);
    }


    public function payWithWallet(User $user, PlayConfig $playConfig, Money $powerPlayValue = null, Order $order =null)
    {
        try {
            if ($playConfig->getPowerPlay()) {
                if($order != null)
                {
                    $powerPlayValue = new Money($order->getLottery()->getPowerPlayValue(), new Currency('EUR'));
                }
                $price = $powerPlayValue->add($playConfig->getSinglePrice());
                $user->pay($price);
            } else {
                $user->pay($playConfig->getSinglePrice());
            }
            $this->entityManager->flush($user);
        } catch (\Exception $e) {
        }
    }

    public function paySubscriptionWithWallet(User $user, PlayConfig $playConfig, $powerPlayValue = null, Order $order = null)
    {
        try {
            if ($playConfig->getPowerPlay()) {
                if($order != null)
                {
                    $powerPlayValue = new Money($order->getLottery()->getPowerPlayValue(), new Currency('EUR'));
                }
                $price = $playConfig->getSinglePrice()->multiply($playConfig->getFrequency());
                $powerPlayValue = $powerPlayValue->multiply($playConfig->getFrequency());
                $price = $price->add($powerPlayValue);
                $user->removeSubscriptionWithWallet($price);
            } else {
                $user->removeSubscriptionWithWallet($playConfig->getSinglePrice()->multiply($playConfig->getFrequency()));
            }
            $this->entityManager->flush($user);
        } catch (\Exception $e) {
            //TODO: Log and warn the admin
        }
    }

    public function paySubscriptionWithWalletAndCreditCard(User $user, PlayConfig $playConfig, $powerPlayValue = null, Order $order = null)
    {
        try {

            if ($playConfig->getPowerPlay()) {
                if($order != null)
                {
                    $powerPlayValue = new Money($order->getLottery()->getPowerPlayValue(), new Currency('EUR'));
                }
                $price = $playConfig->getSinglePrice()->multiply($playConfig->getFrequency());
                $powerPlayValue = $powerPlayValue->multiply($playConfig->getFrequency());
                $price = $price->add($powerPlayValue);
                $user->removeWalletToSubscription($price);
            } else {
                $user->removeWalletToSubscription($playConfig->getSinglePrice()->multiply($playConfig->getFrequency()));
            }

            $this->entityManager->flush($user);
        } catch (\Exception $e) {
            //TODO: Log and warn the admin
        }
    }


    public function payWithSubscription(User $user, PlayConfig $playConfig, $powerPlayValue = null, Order $order = null)
    {
        try {
            if ($playConfig->getPowerPlay()) {
                $powerPlayValue = new Money($playConfig->getLottery()->getPowerPlayValue(), new Currency('EUR'));
                $price = $playConfig->getSinglePrice()->add($powerPlayValue);
                $user->removeSubscriptionWallet($price);
            } else {
                $user->removeSubscriptionWallet($playConfig->getSinglePrice());
            }
            $this->entityManager->flush($user);

        } catch (\Exception $e) {
            //EMTD Log and warn the admin
        }
    }

    public function payGroupedBetsWithWallet(User $user, Money $totalBets)
    {
        try {
            $user->pay($totalBets);
            $this->entityManager->flush($user);
        } catch (\Exception $e) {
            //EMTD Log and warn the admin
        }
    }

    public function purchaseTransactionGrouped(User $user, $transactionType, $data)
    {
        $data['now'] = new \DateTime();
        $data['walletAfter'] = $user->getWallet();
        $data['user'] = $user;
        $this->transactionService->storeTransaction($transactionType, $data);
    }


    public function withDraw(User $user, Money $amount)
    {
        try {
            $walletBefore = $user->getWallet();
            $newWallet = $user->getWallet()->withdraw($amount);
            if ($newWallet == null) {
                throw new \Exception('You don\'t have enough winning amount to complete transaction');
            }
            $user->setWallet($newWallet);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $data = [];
            $data['now'] = new \DateTime();
            $data['walletBefore'] = $walletBefore;
            $data['walletAfter'] = $user->getWallet();
            $data['user'] = $user;
            $data['accountBankId'] = '1';
            $data['amountWithdrawed'] = $amount->getAmount();
            $data['state'] = 'pending';
            $this->transactionService->storeTransaction(TransactionType::WINNINGS_WITHDRAW, $data);
            return new ActionResult(true);
        } catch (\Exception $e) {
            return new ActionResult(false, $e->getMessage());
            //EMTD Log and warn the admin
        }
    }

    public function getWalletDTO(User $user)
    {
        if ($user != null) {
            try {
                $wallet = $user->getWallet();
                $current_winnnings_convert = $this->currencyConversionService->convert($user->getWinningAbove(), $user->getUserCurrency());
                $amount_current_winning = $this->currencyConversionService->toString($current_winnnings_convert, $user->getLocale());
                $amount_balance = $this->currencyConversionService->toString(
                    $this->currencyConversionService->convert($wallet->getBalance(), $user->getUserCurrency()),
                    $user->getLocale()
                );
                $amount_winnings = $this->currencyConversionService->toString(
                    $this->currencyConversionService->convert($wallet->getWithdrawable(), $user->getUserCurrency()),
                    $user->getLocale()
                );
                $amount_subscription = $this->currencyConversionService->toString(
                    $this->currencyConversionService->convert($wallet->getSubscription(), $user->getUserCurrency()),
                    $user->getLocale()
                );
                $amountSubscriptionBalanceEuroMillions = $this->currencyConversionService->toString( $this->currencyConversionService->convert(
                    $this->transactionService->getSubscriptionByLotteryAndUserId('EuroMillions', $user->getId()),
                    $user->getUserCurrency()
                ), $user->getLocale());
                $amountSubscriptionBalancePowerBall = $this->currencyConversionService->toString( $this->currencyConversionService->convert(
                    $this->transactionService->getSubscriptionByLotteryAndUserId('PowerBall', $user->getId()),
                    $user->getUserCurrency()
                ), $user->getLocale());
                $amountSubscriptionBalanceMegaMillions = $this->currencyConversionService->toString( $this->currencyConversionService->convert(
                    $this->transactionService->getSubscriptionByLotteryAndUserId('MegaMillions', $user->getId()),
                    $user->getUserCurrency()
                ), $user->getLocale());
                $amountSubscriptionBalanceEuroJackpot = $this->currencyConversionService->toString( $this->currencyConversionService->convert(
                    $this->transactionService->getSubscriptionByLotteryAndUserId('EuroJackpot', $user->getId()),
                    $user->getUserCurrency()
                ), $user->getLocale());
                $wallet_dto = new WalletDTO([
                    'amountBalance' => $amount_balance,
                    'amountWinnings' => $amount_winnings,
                    'amountCurrentWinning' => $amount_current_winning,
                    'amountSubscription' => $amount_subscription,
                    'currentWinningConvert' => $current_winnnings_convert,
                    'amountSubscriptionBalanceEuroMillions' => $amountSubscriptionBalanceEuroMillions,
                    'amountSubscriptionBalancePowerBall' => $amountSubscriptionBalancePowerBall,
                    'amountSubscriptionBalanceMegaMillions' => $amountSubscriptionBalanceMegaMillions,
                    'amountSubscriptionBalanceEuroJackpot' => $amountSubscriptionBalanceEuroJackpot,
                ]);
                $balance = $this->currencyConversionService->toString($wallet->getBalance(), $user->getLocale());
                $winnings = $this->currencyConversionService->toString($wallet->getWithdrawable(), $user->getLocale());
                $wallet_dto->setBalance($balance);
                $wallet_dto->setWinnings($winnings);
                $wallet_dto->hasEnoughWinningsBalance($wallet->getWithdrawable());
                $wallet_dto->subscriptionBalanceEuromillions = $this->currencyConversionService->toString( $this->currencyConversionService->convert(
                        new Money((int) $wallet_dto->getSubscriptionBalanceEuromillions(), new Currency('EUR')),
                        $user->getUserCurrency()
                ), $user->getLocale());
                return $wallet_dto;
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    public function getUniqueTransactionId()
    {
        return $this->transactionService->getUniqueTransactionId();
    }


    public function fetchLastTransactionId()
    {
        return $this->transactionService->getLastId();
    }

    public function logMovement()
    {
        //EMTD TO DO
    }

    public function createSubscriptionTransaction(User $user,
                                                  $uniqueID = null,
                                                  Order $order)
    {

        try
        {
            $walletBefore = $user->getWallet();
            $data = $this->buildDepositTransactionData(
                $user,
                null,
                $uniqueID,
                $walletBefore,
                $order
            );
            $this->transactionService->storeTransaction(TransactionType::SUBSCRIPTION_PURCHASE, $data);
        }catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }

    public function createDepositTransaction(User $user,
                                                  $uniqueID = null,
                                                  Order $order)
    {

        try
        {
            $creditCardCharge = $order->getCreditCardCharge();

            $walletBefore = $user->getWallet();
            $data = $this->buildDepositTransactionData(
                $user,
                $creditCardCharge,
                $uniqueID,
                $walletBefore,
                $order
            );
            $this->transactionService->storeTransaction(TransactionType::DEPOSIT, $data);
        }catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param User $user
     * @param CreditCardCharge $creditCardCharge
     * @param $uniqueID
     * @param $walletBefore
     * @return array
     */
    private function buildDepositTransactionData(User $user,
                                                 CreditCardCharge $creditCardCharge = null,
                                                 $uniqueID,
                                                 $walletBefore,
                                                 Order $order = null)
    {

        //This is a big shit
        $isChargeFee = '';
        $finalAmount = '';
        if($creditCardCharge != null)
        {
            $isChargeFee = $creditCardCharge->getIsChargeFee();
            $finalAmount = $creditCardCharge->getFinalAmount()->getAmount();
        }

        $dataTransaction = [
            'lottery_id' => $order != null && $order->getLottery() != null ? $order->getLottery()->getId() : 1,
            'numBets' => count($user->getPlayConfig()),
            'feeApplied' => $isChargeFee,
            'transactionID' => $uniqueID,
            'amountWithWallet' => 0,
            'playConfigs' => $order ? $order->getPlayConfig()[0]->getId() : 0,
            'amount' => $finalAmount,
            'amountWithCreditCard' => $finalAmount,
            'user' => $user,
            'walletBefore' => $walletBefore,
            'walletAfter' => $user->getWallet(),
            'now' => new \DateTime(),
            'lotteryName' =>  $order != null && $order->getLottery() != null ? $order->getLottery()->getName() : '',
            'withWallet' => $order != null ? $order->isIsCheckedWalletBalance() : ''
        ];
        return $dataTransaction;
    }
}