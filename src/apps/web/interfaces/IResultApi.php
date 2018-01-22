<?php
namespace EuroMillions\web\interfaces;

interface IResultApi 
{
    public function getResultForDate($lotteryName, $date);
    public function getResultForDateSecond($lotteryName, $date);
    public function getResultBreakDownForDate($lotteryName, $date);
    public function getResultBreakDownForDateSecond($lotteryName, $date);
    public function getRaffleForDate($lotteryName, $date);
    public function getRaffleForDateSecond($lotteryName, $date);
}