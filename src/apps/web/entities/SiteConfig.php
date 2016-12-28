<?php
namespace EuroMillions\web\entities;

use Money\Currency as MoneyCurrency;
use Money\Money;

class SiteConfig extends EntityBase
{
    protected $id;
    protected $fee;
    protected $fee_to_limit;
    protected $default_currency;

    /**
     * @return MoneyCurrency
     */
    public function getDefaultCurrency()
    {
        return $this->default_currency;
    }

    public function setDefaultCurrency(MoneyCurrency $default_currency)
    {
        $this->default_currency = $default_currency;
    }


    /**
     * @return Money
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * @param Money $fee
     */
    public function setFee($fee)
    {
        $this->fee = $fee;
    }

    /**
     * @return Money
     */
    public function getFeeToLimit()
    {
        return $this->fee_to_limit;
    }

    /**
     * @param Money $fee_to_limit
     */
    public function setFeeToLimit($fee_to_limit)
    {
        $this->fee_to_limit = $fee_to_limit;
    }



}