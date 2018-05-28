<?php


namespace EuroMillions\web\vo;


use antonienko\MoneyFormatter\MoneyFormatter;
use EuroMillions\web\interfaces\IJackpot;

class PowerBallJackpot implements IJackpot
{

    protected $amount;

    private function __construct( $amount )
    {
        $this->amount = $amount;
    }

    public static function fromAmountIncludingDecimals($amount)
    {
        return new static($amount);
    }

    /**
     * @return string
     */
    public function getAmount()
    {
//        if (!$this->isValid()) {
//            return "1500000000";
//        } else {
        return (string)$this->amount;
//        }
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function isValid()
    {
        return ($this->amount >= 10);
    }

    public function getCurrency()
    {
        return 'EUR';
    }

    public function toString()
    {
        return $this->amount;
    }
}