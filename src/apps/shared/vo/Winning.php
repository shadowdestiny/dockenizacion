<?php

namespace EuroMillions\shared\vo;

use Money\Money;

class Winning
{

    /**
     * @var Money
     */
    private $price;
    /**
     * @var Money
     */
    private $thresholdPrice;
    /**
     * @var
     */
    private $lotteryId;

    /**
     * Winning constructor.
     * @param Money $price
     * @param Money $thresholdPrice
     * @param $lotteryId
     */
    public function __construct(Money $price, Money $thresholdPrice, $lotteryId)
    {
        $this->price = $price;
        $this->thresholdPrice = $thresholdPrice;
        $this->lotteryId = $lotteryId;
    }

    /**
     * @return bool
     */
    public function greaterThanOrEqualThreshold(){
        return $this->price->greaterThanOrEqual($this->thresholdPrice);
    }

    /**
     * @return mixed
     */
    public function getLotteryId()
    {
        return $this->lotteryId;
    }

    /**
     * @return Money
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getThresholdPrice()
    {
        return $this->thresholdPrice;
    }


}