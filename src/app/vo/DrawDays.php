<?php


namespace EuroMillions\vo;


use EuroMillions\interfaces\Comparable;

class DrawDays implements Comparable
{

    const FRIDAY = 5;

    const TUESDAY = 2;

    private $draw_days;


    public function __construct($value)
    {
        $arr_days = str_split($value);
        foreach($arr_days as $day){
            $this->draw_days .= $day;
        }
    }

    public function compareTo($value)
    {
        foreach(str_split($this->draw_days) as $day){
            if($value == $day){
                return true;
            }
        }
        return false;
    }



}