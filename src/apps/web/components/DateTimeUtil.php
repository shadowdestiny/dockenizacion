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

    public function checkOpenTicket($timeToRetry = null)
    {
        if (!$timeToRetry) {
            $date_today = new \DateTime();
        } else {
            $date_today = new \DateTime();
            $date_today->setTimestamp($timeToRetry);
        }

        $time_config = $this->di->get('globalConfig')['retry_validation_time'];
        $limit_time = strtotime($date_today->format('Y/m/d '. $time_config['time']));
        return ($timeToRetry < $limit_time);
    }

    public function getNumWeeksBetweenDates( \DateTime $date_ini, \DateTime $date_end )
    {
        $interval = $date_ini->diff($date_end);
        return intval( $interval->days / 7 );
    }

    public function getTimeRemainingToCloseDraw( \DateTime $timeCloseDraw )
    {
        $now = new \DateTime();
        $one_day = date( "w", $timeCloseDraw->getTimestamp());
        $two_day = date( "w", $now->getTimestamp());
        if( $one_day == $two_day ) {
            $barrier_time = $timeCloseDraw->getTimestamp() - 1800;
            //$barrier_time = strtotime($time_close_draw->format('Y-m-d H:i:s') . ' -30 minutes' );
            return ($barrier_time > $now->getTimestamp());
        }
        return false;
    }

    public function restMinutesToCloseDraw( \DateTime $timeToCloseDraw, \DateTime $now = null , $rounded = false)
    {
        if( $now == null ) {
            $now = new \DateTime();
        }
        $barrier_time = $timeToCloseDraw->getTimestamp() - 1800;
        $rest = $barrier_time - $now->getTimestamp();
        if($rounded) {
            $precision = 60 * 5;
            $round = ( round ( $rest /  $precision) * $precision );
            return date('i',$round);
        }
        return $rest / 60;
    }

    public function getCountDownNextDraw( \DateTime $dateNextDraw, \DateTime $now = null )
    {
        if( null == $now ) {
            $now = new \DateTime();
        }

        $remain = $dateNextDraw->diff($now);

        $create_day_message = function(\DateInterval $remain) {
            $message = '';
            if($remain->d) {
                $message .= $remain->d > 1 ? $remain->d . ' days and ' : $remain->d . ' day and ';
            }
            return $message;
        };

        $create_hour_message = function(\DateInterval $remain) {
            $message = '';
            if($remain->h) {
                $message .= $remain->h > 1 ? $remain->h . ' hours ' : $remain->h . ' hour';
            }
            return $message;
        };

        $create_minutes_message = function(\DateInterval $remain) {
            $message = '';
            if($remain->i) {
                $message .= $remain->i > 1 ? $remain->i . ' minutes ' : $remain->i . ' minute';
            }
            return $message;
        };

        return $create_day_message($remain) . $create_hour_message($remain) . $create_minutes_message($remain);
    }

    public function isLastMinuteToDraw( \DateTime $timeCloseDraw )
    {
        $now = new \DateTime();
        $last_minute = $timeCloseDraw->getTimestamp() - 60;
        //$last_minute  = strtotime($time_close_draw->format('Y-m-d H:i:s') . ' -1 minute' );
        return ($now->getTimestamp() > $last_minute);
    }

}