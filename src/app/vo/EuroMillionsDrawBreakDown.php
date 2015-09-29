<?php


namespace EuroMillions\vo;


class EuroMillionsDrawBreakDown
{
    protected $breakdown;

    public function __construct(array $breakdown)
    {
        $this->breakdown = $breakdown;
    }

    public function setBreakDown($breakDown)
    {
        $this->breakdown = $breakDown;
    }

    public function getBreakDown()
    {
        return $this->breakdown;
    }
}