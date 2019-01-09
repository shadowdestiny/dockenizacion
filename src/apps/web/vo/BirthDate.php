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

    protected $value;

    public function __construct($value)
    {

        $this->value = $this->prepareDateValue($value);
        Assertion::notEmpty($this->value);
        Assertion::date($this->value,'Y-m-d');
        parent::__construct($this->value);
    }

    private function prepareDateValue($value)
    {
        $day = explode('-',$value);
        if((int) $day[2] < 9 )
        {
            return $day[0].'-'.$day[1].'-'.'0'.$day[2];
        }
        return $day[0].'-'.$day[1].'-'.$day[2];
    }

    public function toArray()
    {
        return [
            'birth_date_value' => $this->value
        ];
    }
}