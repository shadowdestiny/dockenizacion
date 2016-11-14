<?php


namespace EuroMillions\admin\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\interfaces\IReports;
use EuroMillions\web\repositories\ReportsRepository;

class ReportsService
{

    /** @var IReports $reportsRepository */
    private $reportsRepository;

    public function __construct(IReports $iReports = null)
    {
        $this->reportsRepository = $iReports;
    }

    public function fetchMonthlySales(\DateTime $date)
    {
        $result = $this->reportsRepository->getMonthlySales($date);
        $monthlySales = [];

        return $monthlySales;
    }

    public function fetchSalesDraw()
    {
        return $this->reportsRepository->getSalesDraw();
    }
}
