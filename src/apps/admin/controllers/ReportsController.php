<?php

namespace EuroMillions\admin\controllers;

use EuroMillions\web\services\GeoService;

class ReportsController extends AdminControllerBase
{
    /** @var GeoService $geoService */
    private $geoService;

    public function initialize()
    {
        parent::initialize();
        $this->geoService = $this->domainAdminServiceFactory->getGeoService();
    }

    public function indexAction()
    {

    }

    public function businessReportsGeneralKPIsAction()
    {
        $this->view->setVars([
            'needReportsMenu' => true,
        ]);
    }

    public function businessReportsActivityAction()
    {
        $countries = $this->geoService->countryList();
        sort($countries);
        //key+1, select element from phalcon need index 0 to set empty value
        $countries = array_combine(range(1, count($countries)), array_values($countries));

        $this->view->setVars([
            'needReportsMenu' => true,
            'countryList' => $countries,
        ]);
    }

    public function activityResultAction()
    {
        $this->view->setVars([
            'needReportsMenu' => true,
        ]);

    }

    public function businessReportsSalesDrawAction()
    {
        $this->view->setVars([
            'needReportsMenu' => true,
        ]);
    }

    public function playersReportsAction()
    {
        $this->view->setVars([
            'needReportsMenu' => true,
        ]);
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

    public function customerDataAction()
    {
        $this->view->setVars([
            'needReportsMenu' => true,
            'customerData' => $this->reportsService->fetchCustomerData()
        ]);
    }
}