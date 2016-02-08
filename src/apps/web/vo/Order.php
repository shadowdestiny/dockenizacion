<?php


namespace EuroMillions\web\vo;


use EuroMillions\web\entities\PlayConfig;
use Money\Currency;
use Money\Money;

class Order
{

    /** @var  Money $total */
    private $total;
    /** @var PlayConfig $play_config */
    private $play_config;
    /** @var  Money $fee */
    private $fee;
    /** @var  Money $fee_limit */
    private $fee_limit;
    /** @var Money $single_bet_price */
    private $single_bet_price;
    private $num_lines;
    private $state;


    public function __construct(PlayConfig $play_config, Money $single_bet_price, Money $fee, Money $fee_limit)
    {
        $this->play_config = $play_config;
        $this->single_bet_price = $single_bet_price;
        $this->fee = $fee;
        $this->fee_limit = $fee_limit;
        $this->initialize();
    }

    public function addFunds( Money $amount )
    {
        $this->total = $this->total->add($amount);
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }


    /**
     * @return PlayConfig
     */
    public function getPlayConfig()
    {
        return $this->play_config;
    }

    /**
     * @param mixed $play_config
     */
    public function setPlayConfig($play_config)
    {
        $this->play_config = $play_config;
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
    public function getFeeLimit()
    {
        return $this->fee_limit;
    }

    /**
     * @param mixed $fee_limit
     */
    public function setFeeLimit($fee_limit)
    {
        $this->fee_limit = $fee_limit;
    }

    /**
     * @return mixed
     */
    public function getSingleBetPrice()
    {
        return $this->single_bet_price;
    }

    /**
     * @param mixed $single_bet_price
     */
    public function setSingleBetPrice($single_bet_price)
    {
        $this->single_bet_price = $single_bet_price;
    }

    /**
     * @return mixed
     */
    public function getNumLines()
    {
        return $this->num_lines;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    private function initialize()
    {
        $this->num_lines = count($this->play_config->getLine());
        $this->total = new Money(1,new Currency('EUR'));
        $this->total = $this->total->multiply($this->num_lines)->multiply((int) $this->play_config->getDrawDays()->value())->multiply((int) $this->single_bet_price->getAmount())->multiply($this->play_config->getFrequency());
        $this->total = ($this->fee_limit->getAmount() > $this->total->getAmount()) ? $this->total->add($this->fee) : $this->total;
    }



}