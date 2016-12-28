<?php

namespace EuroMillions\web\vo\dto;

use Money\Currency;
use Money\Money;

class BundlePlayDTO implements \JsonSerializable
{
    public $arrayBundleData;
    public $draws;
    public $description;
    public $priceDescription;
    public $price;
    public $discount;
    public $active;
    public $singleBetPrice;
    public $singleBetPriceWithDiscount;
    public $singleBetPriceWithDiscountConversionCurrrency;

    /**
     * @param array $arrayBundleData
     * @param Money $singleBetPrice
     */
    public function __construct(array $arrayBundleData, Money $singleBetPrice)
    {
        $this->arrayBundleData = $arrayBundleData;
        $this->draws = $arrayBundleData['draws'];
        $this->description = $arrayBundleData['description'];
        $this->priceDescription = $arrayBundleData['price_description'];
        $this->price = $arrayBundleData['price'];
        $this->discount = $arrayBundleData['discount'];
        $this->active = $arrayBundleData['checked'];
        $this->singleBetPrice = $singleBetPrice;
        $this->singleBetPriceWithDiscount = $this->getSingleBetPriceWithDiscount($singleBetPrice, $arrayBundleData['discount']);
    }

    /**
     * @param $singleBetPrice
     * @param $discount
     *
     * @return Money
     */
    private function getSingleBetPriceWithDiscount(Money $singleBetPrice, $discount){
        return new Money((int) round($singleBetPrice->getAmount() / (($discount / 100) +1)), new Currency('EUR'));
    }

    /**
     * @return integer
     */
    public function getDraws()
    {
        return $this->draws;
    }

    /**
     * @param integer $draws
     */
    public function setDraws($draws)
    {
        $this->draws = $draws;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getPriceDescription()
    {
        return $this->priceDescription;
    }

    /**
     * @param string $priceDescription
     */
    public function setPriceDescription($priceDescription)
    {
        $this->priceDescription = $priceDescription;
    }

    /**
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param integer $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param float $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    /**
     * @return string
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param string $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return [
            'draws' => $this->getDraws(),
            'description' => $this->getDescription(),
            'price_description' => $this->getPriceDescription(),
            'price' => $this->getPrice(),
            'discount' => $this->getDiscount(),
            'checked' => $this->getActive(),
            'singleBetPrice' => $this->singleBetPrice->getAmount(),
            'singleBetPriceWithDiscount' => $this->singleBetPriceWithDiscount->getAmount(),
        ];
    }

    /**
     * @return mixed
     */
    public function getSingleBetPriceWithDiscountConversionCurrrency()
    {
        return $this->singleBetPriceWithDiscountConversionCurrrency;
    }

    /**
     * @param mixed $singleBetPriceWithDiscountConversionCurrrency
     */
    public function setSingleBetPriceWithDiscountConversionCurrrency($singleBetPriceWithDiscountConversionCurrrency)
    {
        $this->singleBetPriceWithDiscountConversionCurrrency = $singleBetPriceWithDiscountConversionCurrrency;
    }
}