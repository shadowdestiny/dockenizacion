<?php


namespace EuroMillions\web\vo;


use Money\Money;

class CreditCardChargeEmPay
{


    protected $amount;
    protected $fee;
    protected $feeLimit;

    public function __construct(Money $amount, Money $fee, Money $feeLimit)
    {
        $this->amount = $amount;
        $this->fee = $fee;
        $this->feeLimit = $feeLimit;
    }

    public function getAmount()
    {
        if( $this->amount->lessThan($this->feeLimit) ) {
            return $this->amount->subtract($this->fee);
        }
        return $this->amount;
    }
}