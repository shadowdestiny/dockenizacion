<?php


namespace EuroMillions\vo;


class PlayForm
{

    protected $euroMillionsLines;

    protected $drawDays;

    protected $startDrawDate;

    protected $lastDrawDate;

    protected $amount;

    public function __construct(array $euroMillionsLines,
                                $drawDays,
                                $startDrawDate,
                                LastDrawDate $lastDrawDate)
    {
        $this->euroMillionsLines = $euroMillionsLines;

    }

    public function getEuroMillionsLines()
    {
        return $this->euroMillionsLines;
    }

    public function getDrawDays()
    {
        return $this->drawDays;
    }

    public function getStartDrawDate()
    {
        return $this->startDrawDate;
    }

    public function getLastDrawDate()
    {
        return $this->lastDrawDate;
    }

}