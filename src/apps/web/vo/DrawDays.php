<?php


namespace EuroMillions\web\vo;


use EuroMillions\web\interfaces\Comparable;

class DrawDays implements Comparable
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

}