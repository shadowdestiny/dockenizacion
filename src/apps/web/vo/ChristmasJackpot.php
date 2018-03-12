<?php


namespace EuroMillions\web\vo;


use antonienko\MoneyFormatter\MoneyFormatter;
use EuroMillions\web\interfaces\IJackpot;

class ChristmasJackpot implements IJackpot
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
        if (!$this->isValid()) {
            return "2,3";
        } else {
            return (string)$this->amount;
        }
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function isValid()
    {
        return ($this->amount >= 1);
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