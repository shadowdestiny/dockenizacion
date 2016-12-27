<?php


namespace EuroMillions\web\vo;


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

    protected function getDiscountByFrequency($frequency, $arrayBundle)
    {
        foreach ($arrayBundle as $numberDraws) {
            if ($numberDraws['draws'] == $frequency) {
                return $numberDraws['discount'];
            }
        }
        return 0;
    }

    public function toArray() {
        return [
            'value' => $this->value
        ];
    }
}