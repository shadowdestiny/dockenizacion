<?php

namespace EuroMillions\admin\controllers;

class ReportsController extends AdminControllerBase
{
    public function indexAction()
    {
        $this->view->pick('reports/salesDraw');
        $this->view->setVars([
            'needReportsMenu' => true,
        ]);
    }

    public function salesDrawAction()
    {
        $this->reportsService->fetchSalesDraw();
        $this->view->setVars([
            'needReportsMenu' => true,
            'salesDraw' => $this->reportsService->fetchSalesDraw()
        ]);
    }

    public function monthlySalesAction()
    {
        $this->reportsService->fetchMonthlySales();
        $this->view->setVars([
            'needReportsMenu' => true,
        ]);
    }
}