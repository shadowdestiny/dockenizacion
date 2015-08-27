<?php
namespace EuroMillions\vo;

use Assert\Assertion;
use EuroMillions\interfaces\IQueryString;
use EuroMillions\vo\base\StringLiteral;

class QueryString extends StringLiteral implements IQueryString
{
    public function __construct($value)
    {
        Assertion::regex($value, '/^\?([\w\.\-[\]~&%+]+(=([\w\.\-~&%+]+)?)?)*$/');

        parent::__construct($value);
    }
}