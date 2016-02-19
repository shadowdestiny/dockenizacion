<?php


namespace EuroMillions\web\vo;


use Money\Money;

class CreditCardCharge
{

    private $amount;
    private $fee;
    private $fee_to_limit;
    private $is_charge_fee;

    public function __construct( Money $amount, Money $fee, Money $fee_to_limit )
    {
        $this->amount = $amount;
        $this->fee = $fee;
        $this->fee_to_limit = $fee_to_limit;
        $this->is_charge_fee = false;
    }

    public function getFinalAmount()
    {
        if( (int) $this->amount->getAmount() < (int) $this->fee_to_limit->getAmount() ) {
            $this->is_charge_fee = true;
            return $this->amount->add($this->fee);
        } else {
            return $this->amount;
        }
    }

    public function getIsChargeFee()
    {
        $this->getFinalAmount();
        return $this->is_charge_fee;
    }

    public function getNetAmount()
    {
        return $this->amount;
    }

    public function getFee()
    {
        return $this->fee;
    }


}