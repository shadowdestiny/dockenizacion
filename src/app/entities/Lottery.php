<?php

namespace EuroMillions\entities;

use Doctrine\Common\Collections\ArrayCollection;
use EuroMillions\interfaces\IEntity;

class Lottery extends EntityBase implements IEntity
{
    protected $id;
    protected $name;
    protected $active;
    protected $draws;
    protected $frequency; //d, w0100100, m24, y1225
    protected $draw_time; //utc
    protected $jackpot_api;

    public function getJackpotApi()
    {
        return $this->jackpot_api;
    }

    public function setJackpotApi($jackpot_api)
    {
        $this->jackpot_api = $jackpot_api;
    }

    public function __construct()
    {
        $this->draws = new ArrayCollection();
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

    /**
     * @param string $now
     * @return \DateTime
     */
    public function getNextDrawDate($now = null)
    {
        if (!$now) {
            $now = date('Y-m-d H:i:s');
        }
        $strategy = substr($this->frequency, 0, 1);
        $function = 'getNextDrawFrom';
        switch ($strategy) {
            case 'y':
                $function .= 'Yearly';
                break;
            case 'm':
                $function .= 'Monthly';
                break;
            case 'w':
                $function .= 'Weekly';
                break;
            case 'd':
                $function .= 'Daily';
                break;
            default:
                var_dump($this->frequency);
                //throw?
        }
        return $this->$function(substr($this->frequency, 1),  new \DateTime($now));
    }

    protected function getNextDrawFromDaily($configParams, $date)
    {
        if ($date->format("H:i:s") > $this->draw_time) {
            return new \DateTime($date->add(new \DateInterval('P1D'))->format("Y-m-d {$this->draw_time}"));
        } else {
            return new \DateTime($date->format("Y-m-d {$this->draw_time}"));
        }
    }

    protected function getNextDrawFromWeekly($configParams, $date)
    {
        $weekday_index = (int)$date->format('N')-1;
        $result_date = new \DateTime($date->format("Y-m-d {$this->draw_time}"));
        $hour = $date->format("H:i:s");
        $one_day = new \DateInterval('P1D');
        $days_to_check = 7;
        while ($days_to_check) {
            if (1 == (int)$configParams[$weekday_index]  && ($days_to_check < 7 || $hour < $this->draw_time)) {
                return $result_date;
            } else {
                $result_date = $result_date->add($one_day);
                $weekday_index = ($weekday_index + 1 ) % 7;
            }
            $days_to_check--;
        }
        //throw not found
    }

    protected function getNextDrawFromMonthly($configParams, $date)
    {
        $day_of_month = (int)$date->format('d');
        $hour = $date->format("H:i:s");
        $leap_year = $date->format('L');
        $month = $date->format("m");
        if ($day_of_month < (int)$configParams || ($day_of_month == (int)$configParams) && $hour < $this->draw_time) {
            if ($month != 2
                || ($month == 2 &&
                    ($configParams <=28) ||
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

    protected function getNextDrawFromYearly($configParams, $date)
    {
        $month_day = $date->format('md');
        $hour = $date->format('H:i:s');
        $draw_month = substr($configParams, 0, 2);
        $draw_day = substr($configParams, 2,2);
        if (
            ($month_day == $configParams && $hour < $this->draw_time) ||
            ($month_day < $configParams)
        ) {
            return new \DateTime($date->format("Y-{$draw_month}-{$draw_day} {$this->draw_time}"));
        } else {
            return new \DateTime($date->add(new \DateInterval('P1Y'))->format("Y-{$draw_month}-{$draw_day} {$this->draw_time}"));
        }
    }

}