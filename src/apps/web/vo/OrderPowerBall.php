<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 7/06/18
 * Time: 15:39
 */

namespace EuroMillions\web\vo;


use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;
use Money\Currency;
use Money\Money;

class OrderPowerBall extends Order
{
    private $powerPlayPrice;

    private $powerPlay;

    public function __construct(array $play_config, Money $single_bet_price, Money $fee, Money $fee_limit, Discount $discount = null, $withWallet)
    {
        parent::__construct($play_config, $single_bet_price, $fee, $fee_limit, $discount, $withWallet);
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
        $multiplier = $frequency;
        $price = new Money(0, new Currency('EUR'));
        if ($this->powerPlay) {
            $pwprice = $this->powerPlayPrice * count($this->play_config);

            $price = new Money($pwprice, new Currency('EUR'));
            $price = $price->multiply($multiplier);
        }
        $total = $this->total->add($price);
        $this->total = $total;
        $this->credit_card_charge = new CreditCardCharge($total, $this->fee, $this->fee_limit);

    }

    public function getAmount()
    {
        $numPlayConfigs = count($this->getPlayConfig());
        if($this->powerPlay)
        {
            return ($this->lottery->getPowerPlayValue() * $numPlayConfigs) +
                $this->lottery->getSingleBetPrice()->multiply($numPlayConfigs)->getAmount();
        }

        $this->lottery->getSingleBetPrice()->multiply($numPlayConfigs)->getAmount();
    }

    public function setLottery($lottery)
    {
        /** @var Lottery $lottery */
        $this->lottery = $lottery;
        $this->setPowerPlayPrice($lottery->getPowerPlayValue());
    }

    public function getCreditCardCharge()
    {
        if($this->powerPlay)
        {
            $powerPlayValue = (new Money($this->lottery->getPowerPlayValue(), new Currency('EUR')))->multiply(count($this->play_config));
            $this->total =  $this->total->add($powerPlayValue);
        }
        return $this->credit_card_charge = new CreditCardCharge($this->total, $this->fee, $this->fee_limit);
    }

    public function getUnitPrice()
    {
        if($this->getPowerPlay())
        {
           return  $this->getPlayConfig()[0]->getSinglePrice()->add($this->getPowerPlayPrice());
        }
         return $this->getPlayConfig()[0]->getSinglePrice();
    }

    public function getUnitPriceSubscription()
    {
        if($this->getPowerPlay())
        {
            $powerPlay = $this->getPowerPlayPrice()->multiply($this->getPlayConfig()[0]->getFrequency());
            $price = $this->getPlayConfig()[0]->getSinglePrice()->multiply($this->getPlayConfig()[0]->getFrequency());
            $sum =  $price->add($powerPlay);
            return $sum;
        }
        return $this->getPlayConfig()[0]->getSinglePrice()->multiply($this->getPlayConfig()[0]->getFrequency());
    }


}