<?php
namespace EuroMillions\web\interfaces;

interface IJackpotApi 
{
    public function getJackpotForDate($lotteryName, $date);
}