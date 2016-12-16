<?php

namespace EuroMillions\web\vo\dto;

class BundlePlayDTO implements \JsonSerializable
{
    public $draws;
    public $description;
    public $priceDescription;
    public $price;
    public $discount;
    public $active;

    /**
     * @param integer $draws
     * @param string $description
     * @param string $priceDescription
     * @param integer $price
     * @param float $discount
     * @param string $active
     */
    public function __construct($draws, $description, $priceDescription, $price, $discount, $active)
    {
        $this->draws = $draws;
        $this->description = $description;
        $this->priceDescription = $priceDescription;
        $this->price = $price;
        $this->discount = $discount;
        $this->active = $active;
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
        ];
    }
}