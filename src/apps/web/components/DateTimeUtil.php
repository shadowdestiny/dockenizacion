<?php


namespace EuroMillions\web\components;


class DateTimeUtil
{

    protected $date_time;

    public function getDayOfWeek(\DateTime $date_time)
    {
        return (int) date('w',$date_time->getTimestamp());
    }

}