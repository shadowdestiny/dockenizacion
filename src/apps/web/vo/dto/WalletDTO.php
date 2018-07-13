<?php

namespace EuroMillions\web\vo\dto;

use Money\Currency;
use Money\Money;

class WalletDTO
{

    public $wallet_balance_amount;
    public $wallet_uploaded_amount;
    public $wallet_winning_amount;
    public $wallet_subscription_amount;
    public $current_winnings;
    public $balance;
    public $winnings;
    public $hasEnoughWinnings;
    public $subscriptionBalanceEuromillions;
    public $subscriptionBalancePowerBall;
    private $limitWithdrawWinning;


    public function __construct(array $data)
    {
        $this->wallet_balance_amount = $data['amountBalance'];
        $this->wallet_winning_amount = $data['amountWinnings'];
        $this->wallet_subscription_amount = $data['amountSubscription'];
        $this->current_winnings = $data['currentWinningConvert']->isZero() ? '' : $data['currentWinningConvert'];
        $this->subscriptionBalanceEuromillions =$data['amountSubscriptionBalanceEuroMillions'];
        $this->subscriptionBalancePowerBall= $data['amountSubscriptionBalancePowerBall'];
        $this->limitWithdrawWinning = new Money((int) 2500, new Currency('EUR'));
        $this->checkLaterSubscriptionsWithoutRelations();
    }

    /**
     * @return mixed
     */
    public function getWalletBalanceAmount()
    {
        return $this->wallet_balance_amount;
    }

    /**
     * @return mixed
     */
    public function getWalletSubscriptionAmount()
    {
        return $this->wallet_subscription_amount;
    }

    /**
     * @return mixed
     */
    public function getWalletUploadedAmount()
    {
        return $this->wallet_uploaded_amount;
    }

    /**
     * @return mixed
     */
    public function getWalletWinningAmount()
    {
        return $this->wallet_winning_amount;
    }

    /**
     * @return mixed
     */
    public function getCurrentWinnings()
    {
        return $this->current_winnings;
    }

    /**
     * @return float
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param float $balance
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    /**
     * @return float
     */
    public function getWinnings()
    {
        return $this->winnings;
    }

    /**
     * @param float $winnings
     */
    public function setWinnings($winnings)
    {
        $this->winnings = $winnings;
    }

    public function hasEnoughWinningsBalance( Money $amount )
    {
        $this->hasEnoughWinnings = $amount->greaterThan($this->limitWithdrawWinning);
    }

    /**
     * @return mixed
     */
    public function getHasEnoughWinnings()
    {
        return $this->hasEnoughWinnings;
    }

    /**
     * @return mixed
     */
    public function getSubscriptionBalanceEuromillions()
    {
        return $this->subscriptionBalanceEuromillions;
    }

    /**
     * @param mixed $subscriptionBalanceEuromillions
     */
    public function setSubscriptionBalanceEuromillions($subscriptionBalanceEuromillions)
    {
        $this->subscriptionBalanceEuromillions = $subscriptionBalanceEuromillions;
    }

    /**
     * @return mixed
     */
    public function getSubscriptionBalancePowerBall()
    {
        return $this->subscriptionBalancePowerBall;
    }

    /**
     * @param mixed $subscriptionBalancePowerBall
     */
    public function setSubscriptionBalancePowerBall($subscriptionBalancePowerBall)
    {
        $this->subscriptionBalancePowerBall = $subscriptionBalancePowerBall;
    }

    private function checkLaterSubscriptionsWithoutRelations()
    {
        $euroMillionsSubscription = str_replace(['€','.'],"", $this->subscriptionBalanceEuromillions);
        $this->subscriptionBalanceEuromillions = ((int) $euroMillionsSubscription > 0) ?
            $this->subscriptionBalanceEuromillions : $this->wallet_subscription_amount;
        $powerBallSubscription = str_replace(['€','.'],"", $this->subscriptionBalancePowerBall);
        $walletSubscriptionBalance = str_replace(['€','.',','],"", $this->wallet_subscription_amount);
        $sumLotteries = $euroMillionsSubscription + $powerBallSubscription;
        if($sumLotteries !== $walletSubscriptionBalance)
        {
            $substractBalances = $walletSubscriptionBalance - $sumLotteries;
            $this->subscriptionBalanceEuromillions = $substractBalances + $euroMillionsSubscription;
        }
    }

}