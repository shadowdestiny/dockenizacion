<?php

namespace EuroMillions\admin\controllers;

class ReportsController extends AdminControllerBase
{
    public function indexAction()
    {

    }

    public function salesDrawAction()
    {
        $this->view->setVars([
            'needReportsMenu' => true,
            'salesDraw' => $this->reportsService->fetchSalesDraw()
        ]);
    }

    public function monthlySalesAction()
    {
        $this->view->setVars([
            'needReportsMenu' => true,
            'monthlySales' => $this->reportsService->fetchMonthlySales()
        ]);
    }
}