<?php
namespace EuroMillions\web\vo;

use Assert\Assertion;
use EuroMillions\web\interfaces\IQueryString;
use EuroMillions\web\vo\base\StringLiteral;

class QueryString extends StringLiteral implements IQueryString
{
    public function __construct($value)
    {
        Assertion::regex($value, '/^\?([\w\.\-[\]~&%+]+(=([\w\.\-~&%+]+)?)?)*$/');

        parent::__construct($value);
    }
}