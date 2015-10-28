<?php
namespace EuroMillions\web\vo;

use Assert\Assertion;
use EuroMillions\web\vo\base\StringLiteral;

final class SchemeName extends StringLiteral
{
    public function __construct($value)
    {
        Assertion::regex($value, '/^[a-z]([a-z0-9\+\.-]+)?$/i');
        parent::__construct($value);
    }
}