<?php

namespace EuroMillions\web\vo\cutoff;

abstract class CutOff
{
    protected $drawDate;

    protected $dateToCompare;

    public function __construct(\DateTime $drawDate)
    {
        $this->dateToCompare =  new \DateTime();
        $this->drawDate = $drawDate;
    }

    abstract protected function getCloseTime();

    abstract protected function getTimeToSub();

    public function setDateToCompare($date)
    {
        $this->dateToCompare = $date;

        return $this;
    }

    public function isClosed()
    {
        $closeTime = $this->getCloseTimeByDay();

        return $closeTime ? $this->isDatePassed($closeTime) : false;
    }

    protected function getDayOfWeek()
    {
        return (int) date('w',$this->drawDate->getTimestamp());
    }

    public function getCloseTimeByDay()
    {
        $dow = $this->getDayOfWeek();
        $closeTime = $this->getCloseTime();

        return isset($closeTime[$dow]) ? $closeTime[$dow] : null;
    }

    protected function isDatePassed($closeTime)
    {
        $now = $this->dateToCompare;
        $nextDrawDate = \DateTime::createFromFormat('Y-m-d H:i', $this->drawDate->format('Y-m-d').' '.$closeTime);
        $datetimeFrom = $nextDrawDate->sub(\DateInterval::createFromDateString($this->getTimeToSub()))->format('Y-m-d H:i');

        return $now->format('Y-m-d H:i') > $datetimeFrom;
    }
}