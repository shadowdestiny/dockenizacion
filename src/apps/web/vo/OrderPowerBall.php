<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 7/06/18
 * Time: 15:39
 */

namespace EuroMillions\web\vo;


use EuroMillions\web\entities\PlayConfig;
use Money\Currency;
use Money\Money;

class OrderPowerBall extends Order
{
    private $powerPlayPrice;

    private $powerPlay;

    public function __construct(array $play_config, Money $single_bet_price, Money $fee, Money $fee_limit, Discount $discount = null)
    {
        parent::__construct($play_config, $single_bet_price, $fee, $fee_limit, $discount);
        $this->powerPlay = (int)$play_config[0]->getPowerPlay();
    }

    /**
     * @return null
     */
    public function getPowerPlay()
    {
        return $this->powerPlay;
    }

    /**
     * @param null $powerPlay
     */
    public function setPowerPlay($powerPlay)
    {
        $this->powerPlay = $powerPlay;
    }

    /**
     * @return mixed
     */
    public function getPowerPlayPrice()
    {
        return $this->powerPlayPrice;
    }

    /**
     * @param mixed $powerPlayPrice
     */
    public function setPowerPlayPrice($powerPlayPrice)
    {
        $this->powerPlayPrice = $powerPlayPrice;
    }

    public function addFunds(Money $amount = null)
    {
        if ($amount == null) {
            $amount = new Money(0, new Currency('EUR'));
        }
        $frequency = $this->play_config[0]->getFrequency();
        $multiplier = count($this->play_config) * $frequency;
        $price = 0;
        if ($this->powerPlay) {
            $price = new Money($this->powerPlayPrice, new Currency('EUR'));
            $price->add($price->multiply($multiplier));
            $this->funds_amount = ($price);
        }

        $total = $this->total->add($this->funds_amount);

        $this->credit_card_charge = new CreditCardCharge($total, $this->fee, $this->fee_limit);


    }
}