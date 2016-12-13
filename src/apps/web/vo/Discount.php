<?php


namespace EuroMillions\web\vo;


use EuroMillions\web\vo\dto\BundlePlayDTO;

class Discount
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }
    
    public function getDiscountByFrequency($frequency)
    {
        /* @var BundlePlayDTO $numberDraws*/
        foreach ($this->value as $numberDraws) {
            if ($numberDraws->getDraws() == $frequency) {
                return $numberDraws->getDiscount();
            }
        }

        return 0;
    }
}