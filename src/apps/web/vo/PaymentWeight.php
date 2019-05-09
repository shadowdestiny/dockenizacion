<?php


namespace EuroMillions\web\vo;


class PaymentWeight
{


    protected $value;

    public function __construct($value)
    {
        if(!is_integer($value))
        {
            throw new \InvalidArgumentException('Value should be an integer');
        }
        $this->value= $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function toArray() {
        return [
            'value' => $this->value
        ];
    }

}