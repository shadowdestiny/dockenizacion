<?php
namespace EuroMillions\web\vo;

use EuroMillions\web\exceptions\InvalidNativeArgumentException;
use EuroMillions\web\vo\base\StringLiteral;

class Path extends StringLiteral
{
    public function __construct($value)
    {
        $filtered_value = \parse_url($value, PHP_URL_PATH);
        if (null === $filtered_value || \strlen($filtered_value) != \strlen($value)) {
            throw new InvalidNativeArgumentException($value, ['string (valid url path)']);
        }
        parent::__construct($filtered_value);
    }
}