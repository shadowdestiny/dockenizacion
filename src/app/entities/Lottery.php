<?php

namespace EuroMillions\entities;

use antonienko\PositiveModulus\PositiveModulus;
use EuroMillions\interfaces\IEntity;

class Lottery extends EntityBase implements IEntity
{
    protected $id;
    protected $name;
    protected $active;
    protected $frequency; //d, w0100100, m24, y1225
    protected $draw_time; //utc
    protected $jackpot_api;
    protected $result_api;

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

    /**
     * @param \DateTime $now
     * @return \DateTime
     */
    public function getNextDrawDate(\DateTime $now = null)
    {
        return $this->getDrawDate($now, 'Next');
    }

    protected function getLastDrawFromDaily(\DateTime $date)
    {
        if ($date->format("H:i:s") <= $this->draw_time) {
            return new \DateTime($date->sub(new \DateInterval('P1D'))->format("Y-m-d {$this->draw_time}"));
        } else {
            return new \DateTime($date->format("Y-m-d {$this->draw_time}"));
        }
    }

    protected function getNextDrawFromDaily(\DateTime $date)
    {
        if ($date->format("H:i:s") > $this->draw_time) {
            return new \DateTime($date->add(new \DateInterval('P1D'))->format("Y-m-d {$this->draw_time}"));
        } else {
            return new \DateTime($date->format("Y-m-d {$this->draw_time}"));
        }
    }

    protected function getLastDrawFromWeekly($configParams, \DateTime $date)
    {
        $weekday_index = (int)$date->format('N') - 1;
        $result_date = new \DateTime($date->format("Y-m-d {$this->draw_time}"));
        $hour = $date->format("H:i:s");
        $one_day = new \DateInterval('P1D');
        $days_to_check = 7;
        while ($days_to_check) {
            if (1 == (int)$configParams[$weekday_index] && ($days_to_check < 7 || $hour > $this->draw_time)) {
                return $result_date;
            } else {
                $result_date = $result_date->sub($one_day);
                $weekday_index = PositiveModulus::calc($weekday_index - 1, 7);
            }
            $days_to_check--;
        }
        return false; //throw instead?
    }

    protected function getNextDrawFromWeekly($configParams, \DateTime $date)
    {
        $weekday_index = (int)$date->format('N') - 1;
        $result_date = new \DateTime($date->format("Y-m-d {$this->draw_time}"));
        $hour = $date->format("H:i:s");
        $one_day = new \DateInterval('P1D');
        $days_to_check = 7;
        while ($days_to_check) {
            if (1 == (int)$configParams[$weekday_index] && ($days_to_check < 7 || $hour < $this->draw_time)) {
                return $result_date;
            } else {
                $result_date = $result_date->add($one_day);
                $weekday_index = ($weekday_index + 1) % 7;
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
                return new \DateTime($date->format("Y-m-{$configParams} {$this->draw_time}"));
            } else {
                return new \DateTime($date->format("Y-03-{$configParams} {$this->draw_time}"));
            }
        } else {
            $next_month = $date->add(new \DateInterval('P1M'));
            return new \DateTime($next_month->format("Y-m-{$configParams} {$this->draw_time}"));
        }
    }

    protected function getLastDrawFromMonthly($configParams, \DateTime $date)
    {
        $day_of_month = (int)$date->format('d');
        $hour = $date->format("H:i:s");
        $leap_year = $date->format('L');
        $month = $date->format("m");
        if ($day_of_month > (int)$configParams || ($day_of_month == (int)$configParams) && $hour > $this->draw_time) {
            return new \DateTime($date->format("Y-m-{$configParams} {$this->draw_time}"));
        } else {
            if ($month != 3
                || ($month == 3 &&
                    ($configParams <= 28) ||
                    ($configParams == 29 && $leap_year)
                )
            ) {
                $previous_month = $date->sub(new \DateInterval('P1M'));
                return new \DateTime($previous_month->format("Y-m-{$configParams} {$this->draw_time}"));
            } else {
                return new \DateTime($date->format("Y-01-{$configParams} {$this->draw_time}"));
            }
        }
    }

    protected function getNextDrawFromYearly($configParams, \DateTime $date)
    {
        $month_day = $date->format('md');
        $hour = $date->format('H:i:s');
        $draw_month = substr($configParams, 0, 2);
        $draw_day = substr($configParams, 2, 2);
        if (
            ($month_day == $configParams && $hour < $this->draw_time) ||
            ($month_day < $configParams)
        ) {
            return new \DateTime($date->format("Y-{$draw_month}-{$draw_day} {$this->draw_time}"));
        } else {
            return new \DateTime($date->add(new \DateInterval('P1Y'))->format("Y-{$draw_month}-{$draw_day} {$this->draw_time}"));
        }
    }

    protected function getLastDrawFromYearly($configParams, \DateTime $date)
    {
        $month_day = $date->format('md');
        $hour = $date->format('H:i:s');
        $draw_month = substr($configParams, 0, 2);
        $draw_day = substr($configParams, 2, 2);
        if (
            ($month_day == $configParams && $hour > $this->draw_time) ||
            ($month_day > $configParams)
        ) {
            return new \DateTime($date->format("Y-{$draw_month}-{$draw_day} {$this->draw_time}"));
        } else {
            return new \DateTime($date->sub(new \DateInterval('P1Y'))->format("Y-{$draw_month}-{$draw_day} {$this->draw_time}"));
        }
    }

    /**
     * @param \DateTime $now
     * @param $next_or_last
     * @return mixed
     */
    private function getDrawDate(\DateTime $now, $next_or_last)
    {
        $function = 'get' . $next_or_last . 'DrawFrom';
        if (!$now) {
            $now = new \DateTime();
        }
        $strategy = substr($this->frequency, 0, 1);
        switch ($strategy) {
            case 'y':
                $function_name = $function . "Yearly";
                return $this->$function_name(substr($this->frequency, 1), $now);
                break;
            case 'm':
                $function_name = $function . "Monthly";
                return $this->$function_name(substr($this->frequency, 1), $now);
                break;
            case 'w':
                $function_name = $function . "Weekly";
                return $this->$function_name(substr($this->frequency, 1), $now);
                break;
            case 'd':
                $function_name = $function . "Daily";
                return $this->$function_name($now);
                break;
            default:
                return false; //throw instead?
        }
    }

}