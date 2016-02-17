<?php


namespace EuroMillions\web\components;


use Phalcon\Di;

class DateTimeUtil
{

    protected $di;

    protected $date_time;


    public function __construct()
    {
        date_default_timezone_set('Europe/Madrid');
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
        return ($time_to_retry < $limit_time);
    }

    public function getNumWeeksBetweenDates( \DateTime $date_ini, \DateTime $date_end )
    {
        $interval = $date_ini->diff($date_end);
        return intval( $interval->days / 7 );
    }

    public function getTimeRemainingToCloseDraw( \DateTime $time_close_draw )
    {
        $now = new \DateTime();
        $one_day = date( "w", $time_close_draw->getTimestamp());
        $two_day = date( "w", $now->getTimestamp());

        if( $one_day == $two_day ) {
            $barrier_time = $time_close_draw->getTimestamp() - 1800;
            //$barrier_time = strtotime($time_close_draw->format('Y-m-d H:i:s') . ' -30 minutes' );
            return ($barrier_time > $now->getTimestamp());
        }

        return false;
    }

    public function restMinutesToCloseDraw( \DateTime $time_close_draw, \DateTime $now = null )
    {
        if( $now == null ) {
            $now = new \DateTime();
        }
        $barrier_time = $time_close_draw->getTimestamp() - 1800;
        $rest = $barrier_time - $now->getTimestamp();
        return $rest / 60;
    }

    public function getCountDownNextDraw( \DateTime $date_next_draw )
    {
        $remain = $date_next_draw->diff(new \DateTime());
        return $remain->d . ' days and ' . $remain->h . ' hours';
    }

    public function isLastMinuteToDraw( \DateTime $time_close_draw )
    {
        $now = new \DateTime();
        $last_minute = $time_close_draw->getTimestamp() - 60;
        //$last_minute  = strtotime($time_close_draw->format('Y-m-d H:i:s') . ' -1 minute' );
        return ($now->getTimestamp() > $last_minute);
    }

}