<?php

namespace EuroMillions\web\vo\dto;


class WalletDTO
{

    public $wallet_balance_amount;
    public $wallet_uploaded_amount;
    public $wallet_winning_amount;
    public $current_winnings;


    public function __construct( $balance, $uploaded, $winnings, $current_winnings )
    {
        $this->wallet_balance_amount = $balance;
        $this->wallet_uploaded_amount = $uploaded;
        $this->wallet_winning_amount = $winnings;
        $this->current_winnings = strpos($current_winnings, '0') == 3 ? null : $current_winnings;
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

}