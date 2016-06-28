<?php

namespace EuroMillions\web\vo\dto;

use Money\Currency;
use Money\Money;

class WalletDTO
{

    public $wallet_balance_amount;
    public $wallet_uploaded_amount;
    public $wallet_winning_amount;
    public $current_winnings;
    public $balance;
    public $winnings;
    public $hasEnoughWinnings;
    private $limitWithdrawWinning;


    public function __construct( $balance, $winnings, $current_winnings, Money $currentWinningsConverted )
    {
        $this->wallet_balance_amount = $balance;
        $this->wallet_winning_amount = $winnings;
        $this->current_winnings = $currentWinningsConverted->isZero() ? '' : $current_winnings;
        $this->limitWithdrawWinning = new Money((int) 2500, new Currency('EUR'));
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

}