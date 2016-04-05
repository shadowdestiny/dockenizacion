<?php


namespace EuroMillions\web\vo;


use EuroMillions\shared\helpers\StringHelper;
use EuroMillions\shared\interfaces\IArraySerializable;
use EuroMillions\web\interfaces\Comparable;
use ReflectionClass;

class DrawDays implements Comparable, IArraySerializable
{

    const FRIDAY = 5;

    const TUESDAY = 2;

    public $days;


    public function __construct($value)
    {
        $arr_days = str_split($value);
        foreach($arr_days as $day){
            $this->days .= $day;
        }
    }

    public function compareTo($value)
    {
        foreach(str_split($this->days) as $day){
            if($value == $day){
                return true;
            }
        }
        return false;
    }

    public function value()
    {
        return $this->days;
    }

    public function value_len()
    {
        return strlen($this->days);
    }

    /** @return array */
    public function toArray()
    {
        $key = StringHelper::fromCamelCaseToUnderscore((new ReflectionClass($this))->getShortName());
        return [$key => $this->value()];
    }
}