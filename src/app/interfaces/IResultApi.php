<?php
namespace EuroMillions\interfaces;

interface IResultApi 
{
    public function getResultForDate($lotteryName, $date);
    public function getResultBreakDownForDate($lotteryName, $date);

}