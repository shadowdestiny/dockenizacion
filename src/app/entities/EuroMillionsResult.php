<?php
namespace EuroMillions\entities;

class EuroMillionsResult extends LotteryResult
{
    protected $regular_numbers;
    protected $lucky_numbers;

    public function setRegularNumbers($regular_numbers)
    {
        $this->regular_numbers = $regular_numbers;
    }

    public function setLuckyNumbers($lucky_numbers)
    {
        $this->lucky_numbers = $lucky_numbers;
    }

    public function getRegularNumbers()
    {
        return $this->regular_numbers;
    }

    public function getLuckyNumbers()
    {
        return $this->lucky_numbers;
    }
}