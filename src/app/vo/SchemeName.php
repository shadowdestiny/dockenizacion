<?php
namespace EuroMillions\vo;

use Assert\Assertion;
use EuroMillions\vo\base\StringLiteral;

final class SchemeName extends StringLiteral
{
    public function __construct($value)
    {
        Assertion::regex($value, '/^[a-z]([a-z0-9\+\.-]+)?$/i');
        parent::__construct($value);
    }
}