<?php


namespace EuroMillions\web\vo;


use Assert\Assertion;
use EuroMillions\web\vo\base\StringLiteral;

class PaymentCountry extends StringLiteral
{

    public function __construct($value)
    {
        Assertion::notEmpty($value);
        parent::__construct($value);
    }


    public static function createPaymentCountry($value)
    {
        return new self($value);
    }

}