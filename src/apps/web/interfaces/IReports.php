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
    public function getEuromillionsDrawDetailsByIdAndDates($id, $drawDates);
    public function getEuromillionsDrawDetailsBetweenDrawDates($drawDates);
    public function getNewRegistrations($data);
    public function getNewDepositors($data);
    public function getActives($data);
    public function getActivesDay($data);
    public function getActivesMonth($data);
    public function getActivesYear($data);
//    public function getNewRegistrationsMobile($data);
//    public function getNewDepositorsMobile($data);
//    public function geConversionMobile($data);
//    public function getActivesMobile($data);
    public function getNumberBets($data);
    public function getTotalBetsAmount($data);
    public function getNumberDeposits($data);
    public function getDepositAmount($data);
    public function getNumberWithdrawals($data);
    public function getWithdrawalAmount($data);
//    public function getGrossGamingRevenue($data);
//    public function getNetGamingRevenue($data);
//    public function getAverageRevenuePerUser($data);
//    public function getBonusCost($data);
    public function getPlayerWinnings($data);
//    public function getCustomerLifetimeValue($data);
}