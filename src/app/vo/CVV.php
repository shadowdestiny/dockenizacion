<?php


namespace EuroMillions\vo;


use EuroMillions\vo\base\StringLiteral;

class CVV extends StringLiteral
{

    public function __construct($cvv)
    {
        parent::__construct($cvv);
    }
}