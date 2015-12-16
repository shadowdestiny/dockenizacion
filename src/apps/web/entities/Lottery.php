<?php

namespace EuroMillions\web\entities;

use antonienko\PositiveModulus\PositiveModulus;
use EuroMillions\web\interfaces\IEntity;
use Money\Money;

class Lottery extends EntityBase implements IEntity
{
    protected $id;
    protected $name;
    protected $active;
    protected $frequency; //d, w0100100, m24, y1225
    protected $draw_time; //utc
    protected $jackpot_api;
    protected $result_api;
    /** @var  Money */
    protected $single_bet_price;

    public function getJackpotApi()
    {
        return $this->jackpot_api;
    }

    public function setJackpotApi($jackpot_api)
    {
        $this->jackpot_api = $jackpot_api;
    }

    public function getResultApi()
    {
        return $this->jackpot_api;
    }

    public function setResultApi($jackpot_api)
    {
        $this->jackpot_api = $jackpot_api;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getFrequency()
    {
        return $this->frequency;
    }

    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;
    }

    public function getDrawTime()
    {
        return $this->draw_time;
    }

    public function setDrawTime($draw_time)
    {
        $this->draw_time = $draw_time;
    }

    public function getLastDrawDate(\DateTime $now = null)
    {
        return $this->getDrawDate($now, 'Last');
    }

    public function setSingleBetPrice($single_bet_price)
    {
        $this->single_bet_price = $single_bet_price;
    }

    public function getSingleBetPrice()
    {
        return $this->single_bet_price;
    }

    /**
     * @param \DateTime $now
     * @return \DateTime
     */
    public function getNextDrawDate(\DateTime $now = null)
    {
        return $this->getDrawDate($now, 'Next');
    }

    public function getRecurringIntervalDrawDate($iteration = 5, \DateTime $now = null)
    {
        return $this->getRecurrentDraw($iteration, $now);
    }

    private function getDateWithDrawTime(\DateTime $date)
    {
        return new \DateTime($date->format("Y-m-d {$this->draw_time}"));
    }

    private function getDateWithSpecificMonthAndDayAndDrawTime(\DateTime $date, $month, $day)
    {
        return new \DateTime($date->format("Y-{$month}-{$day} {$this->draw_time}"));
    }

    protected function getDrawFromDaily(\DateTime $date, $iterationMethod, callable $hourCondition)
    {
        $hour = $date->format("H:i:s");
        if($hourCondition($hour)) {
            return $this->getDateWithDrawTime($date);
        } else {
            return $this->getDateWithDrawTime($date->$iterationMethod(new \DateInterval('P1D')));
        }
    }

    protected function getDrawFromWeekly($configParams, \DateTime $date, $iterationMethod, $increment, callable $hourCondition)
    {
        $weekday_index = (int)$date->format('N') - 1;
        $result_date = $this->getDateWithDrawTime($date);
        $hour = $date->format("H:i:s");
        $one_day = new \DateInterval('P1D');
        $days_to_check = 7;
        while ($days_to_check) {
            if (1 == (int)$configParams[$weekday_index] && ($days_to_check < 7 || $hourCondition($hour))) {
                return $result_date;
            } else {
                $result_date = $result_date->$iterationMethod($one_day);
                $weekday_index = PositiveModulus::calc($weekday_index + $increment, 7);
            }
            $days_to_check--;
        }
        return false; //throw instead?
    }

    protected function getNextDrawFromMonthly($configParams, \DateTime $date)
    {
        $day_of_month = (int)$date->format('d');
        $hour = $date->format("H:i:s");
        $leap_year = $date->format('L');
        $month = $date->format("m");
        if ($day_of_month < (int)$configParams || ($day_of_month == (int)$configParams) && $hour < $this->draw_time) {
            if ($month != 2
                || ($month == 2 &&
                    ($configParams <= 28) ||
                    ($configParams == 29 && $leap_year)
                )
            ) {
                return $this->getDateWithSpecificMonthAndDayAndDrawTime($date,'m',$configParams);
            } else {
                return $this->getDateWithSpecificMonthAndDayAndDrawTime($date,'03',$configParams);
            }
        } else {
            $next_month = $date->add(new \DateInterval('P1M'));
            return $this->getDateWithSpecificMonthAndDayAndDrawTime($next_month,'m',$configParams);
        }
    }

    protected function getLastDrawFromMonthly($configParams, \DateTime $date)
    {
        $day_of_month = (int)$date->format('d');
        $hour = $date->format("H:i:s");
        $leap_year = $date->format('L');
        $month = $date->format("m");
        if ($day_of_month > (int)$configParams || ($day_of_month == (int)$configParams) && $hour > $this->draw_time) {
            return $this->getDateWithSpecificMonthAndDayAndDrawTime($date,'m',$configParams);
        } else {
            if ($month != 3
                || ($month == 3 &&
                    ($configParams <= 28) ||
                    ($configParams == 29 && $leap_year)
                )
            ) {
                $previous_month = $date->sub(new \DateInterval('P1M'));
                return $this->getDateWithSpecificMonthAndDayAndDrawTime($previous_month,'m',$configParams);
            } else {
                return $this->getDateWithSpecificMonthAndDayAndDrawTime($date,'01',$configParams);
            }
        }
    }

    protected function getDrawFromYearly($configParams, \DateTime $date, $iterationMethod, callable $hourCondition, callable $monthDayCondition)
    {
        $month_day = $date->format('md');
        $hour = $date->format('H:i:s');
        $draw_month = substr($configParams, 0, 2);
        $draw_day = substr($configParams, 2, 2);
        if (
            ($month_day == $configParams && $hourCondition($hour)) || $monthDayCondition($month_day)
        ) {
            return $this->getDateWithSpecificMonthAndDayAndDrawTime($date,$draw_month,$draw_day);
        } else {
            return $this->getDateWithSpecificMonthAndDayAndDrawTime($date->$iterationMethod(new \DateInterval('P1Y')),$draw_month,$draw_day);
        }
    }

    /**
     * @param \DateTime $now
     * @param $next_or_last
     * @return mixed
     */
    private function getDrawDate(\DateTime $now = null, $next_or_last)
    {
        $draw_time = $this->draw_time;
        $config_params = substr($this->frequency, 1);
        if ($next_or_last == 'Next') {
            $iteration_method = 'add';
            $increment = 1;
            $hourCondition = function($hour) use ($draw_time) {
                return $hour < $draw_time;
            };
            $monthDayConfition = function ($monthDay) use ($config_params) {
                return $monthDay < $config_params;
            };
        } else {
            $iteration_method = 'sub';
            $increment = -1;
            $hourCondition = function($hour) use ($draw_time) {
                return $hour > $draw_time;
            };
            $monthDayConfition = function ($monthDay) use ($config_params) {
                return $monthDay > $config_params;
            };
        }

        $function = 'get' . $next_or_last . 'DrawFrom';
        if (!$now) {
            $now = new \DateTime();
        }
        $strategy = substr($this->frequency, 0, 1);
        switch ($strategy) {
            case 'y':
                return $this->getDrawFromYearly($config_params, $now, $iteration_method, $hourCondition, $monthDayConfition);
                break;
            case 'm':
                $function_name = $function . "Monthly";
                return $this->$function_name($config_params, $now);
                break;
            case 'w':
                return $this->getDrawFromWeekly($config_params, $now, $iteration_method, $increment, $hourCondition);
                break;
            case 'd':
                return $this->getDrawFromDaily($now, $iteration_method, $hourCondition);
                break;
            default:
                return false; //throw instead?
        }
    }

    private function getRecurrentDraw($iteration = 5, \DateTime $now = null)
    {
        if( !$now ) {
            $now = new \DateTime();
        }
        $drawDates = [];
        for($i=0; $i < $iteration; $i++){
            if($i == 0) $lastDraw = $now;
            /** @var \DateTime $lastDraw */
            $lastDraw = $this->getDrawDate($lastDraw, 'Next');
            $drawDates[] = [ $lastDraw->format('d M Y')];
        }
        return $drawDates;
    }

}