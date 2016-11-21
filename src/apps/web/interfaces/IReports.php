<?php


namespace EuroMillions\web\interfaces;


interface IReports
{
    public function getSalesDraw();
    public function getMonthlySales();
    public function getCustomersData();
}