<?php


namespace EuroMillions\web\vo;


use EuroMillions\web\vo\base\StringLiteral;

class CVV extends StringLiteral
{

    public function __construct($cvv)
    {
        parent::__construct($cvv);
    }
}