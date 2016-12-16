<?php


namespace EuroMillions\web\vo;


use EuroMillions\web\vo\dto\BundlePlayDTO;

class Discount
{
    protected $value;

    public function __construct($frequency, $arrayBundle)
    {
        $this->value = 0;
        if (!empty($arrayBundle)) {
            $this->value = $this->getDiscountByFrequency($frequency, $arrayBundle);
        }
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getDiscountByFrequency($frequency, $arrayBundle)
    {
        /* @var BundlePlayDTO $numberDraws */
        foreach ($arrayBundle as $numberDraws) {
            if ($numberDraws->getDraws() == $frequency) {
                return $numberDraws->getDiscount();
            }
        }

        return 0;
    }
}