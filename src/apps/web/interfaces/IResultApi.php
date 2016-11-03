<?php
namespace EuroMillions\web\interfaces;

interface IResultApi 
{
    public function getResultForDate($lotteryName, $date);
    public function getResultBreakDownForDate($lotteryName, $date);
    public function getRaffleForDate($lotteryName, $date);

}