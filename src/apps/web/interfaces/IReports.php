<?php


namespace EuroMillions\web\interfaces;


interface IReports
{
    public function getSalesDraw();
    public function getMonthlySales();
    public function getCustomersData();
    public function getUsersByReportsPlayersQuery($sql);
    public function getUserAndDataFromTransactionsBetweenDates($dateFrom, $dateTo);
    public function getActivePlayConfigsByUser($userId, $nextDrawDate);
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
    public function getNumbersPlayedByBetId($betId);
    public function getDepositorsD0($data);
    public function getDepositorsD1($data);
    public function getDepositorsD2($data);
    public function getJustInactives($data);
    public function getInactives($data);
    public function getDormant($data);
    public function getReactivatedJI($data);
    public function getReactivatedIN($data);
    public function getReactivatedDOR($data);
    public function getActivesBeginning($data);
    public function getActivesEnd($data);
    public function getGrossGamingRevenue($data);
}