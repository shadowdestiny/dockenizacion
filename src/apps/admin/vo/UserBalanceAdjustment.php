<?php


namespace EuroMillions\admin\vo;

use EuroMillions\shared\vo\Wallet;
use Money\Currency;
use Money\Money;

class UserBalanceAdjustment
{

    /** @var  Wallet */
    private $wallet;
    private $amount;
    private $isWithdrawable;
    private $reason;
    private $isDeduct;


    public function __construct( Wallet $wallet, array $data )
    {
        if( strlen($data['amount']) > 12 ){
            throw new \Exception('Amount field is greater than 12 chars');
        }
        if( strpos($data['amount'],'-') !== false ) {
            $amountSantizated = substr($data['amount'], 1);
            if(!is_numeric($amountSantizated)) {
                throw new \Exception('Only numeric values are allowed.');
            }
            $data['amount'] = $amountSantizated;
            $this->isDeduct = true;
        } else {
            if(!is_numeric(substr($data['amount'],1))) {
                throw new \Exception('Only numeric values are allowed.');
            }
            $this->isDeduct = false;
        }
        if( strlen($data['reason']) > 200 ) {
            throw new \Exception('Reason field exceed limit chars.');
        }

        $this->wallet = $wallet;
        $this->amount = new Money( (int) $data['amount'], new Currency('EUR') );
        $this->isWithdrawable = $data['withdrawable'];
        $this->reason = $data['reason'];
        $this->createAdjustmentBalance();
    }

    private function deductUserBalance()
    {
        $this->wallet = $this->wallet->pay($this->amount);
    }

    private function increaseUserBalance()
    {
        $this->wallet = $this->wallet->upload($this->amount);
    }

    private function adjustmentBalance()
    {
        if($this->isDeduct) {
            $this->deductUserBalance();
        } else {
            $this->increaseUserBalance();
        }
    }

    private function withdrawableUserBalance()
    {
        if($this->isDeduct) {
            $this->wallet = $this->wallet->withdraw($this->amount);
        } else {
            $this->wallet = $this->wallet->award($this->amount);
        }

    }

    public function createAdjustmentBalance()
    {
        if($this->isWithdrawable) {
            $this->withdrawableUserBalance();
        } else {
            $this->adjustmentBalance();
        }
    }

    /**
     * @return Wallet
     */
    public function getWallet()
    {
        return $this->wallet;
    }

    /**
     * @return Money
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return boolean
     */
    public function getIsWithdrawable()
    {
        return $this->isWithdrawable;
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }


}