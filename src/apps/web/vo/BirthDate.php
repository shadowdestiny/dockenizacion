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

        $dateValue = $this->prepareDateValue($value);
        Assertion::notEmpty($dateValue);
        Assertion::date($dateValue,'Y-m-d');
        parent::__construct($dateValue);
    }

    private function prepareDateValue($value)
    {
        $day = explode('-',$value);
        if((int) $day[2] < 9 )
        {
            return $day[0].'-'.$day[1].'-'.'0'.$day[2];
        }
    }
}