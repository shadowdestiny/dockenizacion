<?php


namespace EuroMillions\web\vo;


use http\Exception\InvalidArgumentException;

class PaymentCountry
{

    protected $value;

    public function __construct(array $value)
    {
        $this->value=$value;
    }

    public static function createPaymentCountry(array $value)
    {
        return new self($value);
    }

    public function countries()
    {
        return $this->value;
    }

}