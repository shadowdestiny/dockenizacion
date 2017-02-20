<?php


namespace EuroMillions\web\interfaces;


interface IReports
{
    public function getSalesDraw();
    public function getMonthlySales();
    public function getCustomersData();
    public function getUsersByReportsPlayersQuery($sql);
    public function getUserAndDataFromTransactionsBetweenDates($dateFrom, $dateTo);
    public function getActivePlayConfigsByUser($userId);
    public function getPastGamesWithPrizes($userId);
}