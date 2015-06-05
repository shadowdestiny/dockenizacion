<?php
namespace EuroMillions\interfaces;

interface IJackpotApi 
{
    public function getJackpotForDate($lotteryName, $date);
}