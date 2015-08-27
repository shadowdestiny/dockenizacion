<?php
namespace EuroMillions\vo;

use Assert\Assertion;
use EuroMillions\vo\base\StringLiteral;

class Email extends StringLiteral
{
    public function __construct($value)
    {
        Assertion::notEmpty($value);
        Assertion::email($value);
        parent::__construct($value);
    }
}