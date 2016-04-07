<?php


namespace EuroMillions\web\vo;


use EuroMillions\web\entities\PlayConfig;
use Money\Currency;
use Money\Money;

class Order implements \JsonSerializable
{

    /** @var  Money $total */
    private $total;
    /** @var PlayConfig[] $play_config */
    private $play_config;
    /** @var  Money $fee */
    private $fee;
    /** @var  Money $fee_limit */
    private $fee_limit;
    /** @var Money $single_bet_price */
    private $single_bet_price;
    private $num_lines;
    private $state;
    private $funds_amount;
    /** @var  CreditCardCharge $credit_card_charge */
    private $credit_card_charge;
    /** @var bool $isCheckedWalletBalance */
    private $isCheckedWalletBalance;



    public function __construct(array $play_config, Money $single_bet_price, Money $fee, Money $fee_limit)
    {
        $this->play_config = $play_config;
        $this->single_bet_price = $single_bet_price;
        $this->fee = $fee;
        $this->fee_limit = $fee_limit;
        $this->funds_amount = new Money(0, new Currency('EUR'));
        $this->isCheckedWalletBalance = false;
        $this->initialize();
    }


    public function getCreditCardCharge()
    {
        return $this->credit_card_charge;
    }

    public function addFunds( Money $amount = null )
    {
        if( $amount == null ) {
            $amount = new Money(0, new Currency('EUR'));
        }

//        if($this->isCheckedWalletBalance) {
//            $sumFunds = new Money($this->total->add($amount)->getAmount(), new Currency('EUR'));
//            $total = $sumFunds->subtract($this->total);
//        } else {
//            $total = $this->total->add($amount);
//        }
        $total = $this->total->add($amount);
        $this->credit_card_charge = new CreditCardCharge($total, $this->fee, $this->fee_limit);
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->credit_card_charge->getFinalAmount();
    }

    public function getTotalFromUser()
    {
        return $this->credit_card_charge->getNetAmount();
    }

    public function totalOriginal()
    {
        return $this->total;
    }

    /**
     * @return PlayConfig[]
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

    public function is_charged_fee()
    {
        return $this->credit_card_charge->getIsChargeFee();
    }


    public function isNextDraw( \DateTime $draw_date )
    {
        $play_config = $this->getPlayConfig();
        if($play_config[0]->getStartDrawDate()->getTimestamp() > $draw_date->getTimestamp() ) {
            return false;
        } else {
            return true;
        }
    }


    private function initialize()
    {
        $this->num_lines = count($this->play_config);
        $this->total = new Money(1,new Currency('EUR'));
        $this->total = $this->total->multiply($this->num_lines)->multiply((int) $this->play_config[0]->getDrawDays()->value_len())->multiply((int) $this->single_bet_price->getAmount())->multiply($this->play_config[0]->getFrequency());
        $this->credit_card_charge = new CreditCardCharge($this->total,$this->fee,$this->fee_limit);
    }

    public function toJsonData()
    {
        return json_encode($this);
    }

    /**
     * @return boolean
     */
    public function isIsCheckedWalletBalance()
    {
        return $this->isCheckedWalletBalance;
    }

    /**
     * @param boolean $isCheckedWalletBalance
     */
    public function setIsCheckedWalletBalance($isCheckedWalletBalance)
    {
        $this->isCheckedWalletBalance = $isCheckedWalletBalance;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {

         $bets = [];
        /** @var PlayConfig $play */
        foreach($this->play_config as $play) {
            $bets[] = $play->jsonSerialize();
         }

         return [
            'total' => $this->total->getAmount(),
            'fee' => $this->fee->getAmount(),
            'fee_limit' => $this->fee_limit->getAmount(),
            'single_bet_price' => $this->single_bet_price->getAmount(),
            'num_lines' => $this->num_lines,
            'play_config' => $bets
        ];

    }
}