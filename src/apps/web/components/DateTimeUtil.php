<?php


namespace EuroMillions\web\components;


use Phalcon\Di;

class DateTimeUtil
{

    protected $di;

    protected $date_time;


    public function __construct()
    {
        $this->di = \Phalcon\DI::getDefault();;
    }


    public function getDayOfWeek(\DateTime $date_time)
    {
        return (int) date('w',$date_time->getTimestamp());
    }

    public function checkOpenTicket($time_to_retry = null)
    {
        if(!$time_to_retry) $time_to_retry = strtotime('now');
        $time_config = $this->di->get('globalConfig')['retry_validation_time'];
        $date_today = new \DateTime();
        $limit_time = strtotime($date_today->format('Y/m/d '. $time_config['time']));
        return ($time_to_retry < $limit_time) ? true : false;
    }

    public function getNumWeeksBetweenDates( \DateTime $date_ini, \DateTime $date_end )
    {
        $interval = $date_ini->diff($date_end);
        return intval( $interval->days / 7 );
    }

    public function getTimeRemainingToCloseDraw( \DateTime $time_close_draw )
    {
        $now = new \DateTime();
        return $time_to_remain = $time_close_draw->getTimestamp() - $now->getTimestamp();
    }

}