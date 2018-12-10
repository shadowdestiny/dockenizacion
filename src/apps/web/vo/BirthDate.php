<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * BirthDate: 12/11/18
 * Time: 06:42 PM
 */

namespace EuroMillions\web\vo;

use Assert\Assertion;
use EuroMillions\web\vo\base\StringLiteral;

class BirthDate extends StringLiteral
{
    public function __construct($value)
    {
        Assertion::notEmpty($value);
        Assertion::date($value,'Y-m-d');
        parent::__construct($value);
    }
}