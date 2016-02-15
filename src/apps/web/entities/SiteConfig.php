<?php


namespace EuroMillions\web\entities;


class SiteConfig extends EntityBase
{
    protected $id;
    protected $fee;
    protected $fee_to_limit;
    protected $default_currency;

    /**
     * @return mixed
     */
    public function getDefaultCurrency()
    {
        return $this->default_currency;
    }

    /**
     * @param mixed $default_currency
     */
    public function setDefaultCurrency($default_currency)
    {
        $this->default_currency = $default_currency;
    }


    /**
     * @return mixed
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * @param mixed $fee
     */
    public function setFee($fee)
    {
        $this->fee = $fee;
    }

    /**
     * @return mixed
     */
    public function getFeeToLimit()
    {
        return $this->fee_to_limit;
    }

    /**
     * @param mixed $fee_to_limit
     */
    public function setFeeToLimit($fee_to_limit)
    {
        $this->fee_to_limit = $fee_to_limit;
    }

}