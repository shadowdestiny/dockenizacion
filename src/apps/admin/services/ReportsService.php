<?php


namespace EuroMillions\admin\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\repositories\ReportsRepository;

class ReportsService
{

    private $entityManager;
    /** @var ReportsRepository $reportsRepository */
    private $reportsRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->reportsRepository = $this->entityManager->getRepository('EuroMillions\web\repositories\ReportsRepository');
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
