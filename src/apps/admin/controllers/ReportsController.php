<?php

namespace EuroMillions\admin\controllers;

use EuroMillions\admin\services\TrackingService;
use EuroMillions\shared\components\widgets\PaginationWidget;
use EuroMillions\web\services\GeoService;
use EuroMillions\web\vo\dto\PastDrawsCollectionDTO;
use EuroMillions\web\vo\dto\UpcomingDrawsDTO;

class ReportsController extends AdminControllerBase
{
    /** @var GeoService $geoService */
    private $geoService;
    /** @var TrackingService $trackingService */
    private $trackingService;
    /** @var array $countries */
    private $countries;

    public function initialize()
    {
        parent::initialize();
        $this->trackingService = $this->domainAdminServiceFactory->getTrackingService();
        $this->geoService = $this->domainAdminServiceFactory->getGeoService();
        $this->countries = $this->getCountries();

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
        $this->view->setVars([
            'needReportsMenu' => true,
            'countryList' => $this->countries,
        ]);
    }

    public function activityResultAction()
    {
        echo $this->request->getPost('dateFrom');
        echo '<br />';
        echo $this->request->getPost('dateTo');
        echo '<br />';
        var_dump($this->request->getPost('countries'));
        echo '<br />';
        var_dump($this->request->getPost('groupBy'));


        $this->view->pick('reports/results/_activityResults');
        return $this->view->setVars([
            'test' => $this->request->getPost('dateFrom'),
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
            'countryList' => $this->countries,
            'allTrackingCodes' => $this->trackingService->getAllTrackingCodesWithUsersCount(),
        ]);
    }

    public function playersReportsResultsAction()
    {
        $headerList = $this->reportsService->getPlayersReportsKeys($this->request->getPost());
        $this->view->setVars([
            'needReportsMenu' => true,
            'headerList' => $headerList,
            'playersList' => $this->reportsService->getPlayersReportsResultsByPostData($headerList, $this->request->getPost()),
            'playersGGRList' => $this->reportsService->getGGRPlayersByDates(
                $this->request->getPost('check_ggr'),
                $this->request->getPost('dateFrom'),
                $this->request->getPost('dateTo')
            ),
            'countryList' => $this->countries,
        ]);
    }

    public function playerDetailsAction()
    {
        $user = $this->reportsService->getUserById($this->request->get('id'));

        $myGamesActives = new UpcomingDrawsDTO($this->reportsService->getActivePlaysByUserId($user->getId()));
        $pageActives = (!empty($this->request->get('page'))) ? $this->request->get('page') : 1;
        $paginatorActives = $this->getPaginatorAsArray(!empty($myGamesActives->result) ? $myGamesActives->result : [], 4, $pageActives);
        $paginatorViewActives = (new PaginationWidget($paginatorActives, $this->request->getQuery()))->render();

        $myGamesInactives = new PastDrawsCollectionDTO($this->reportsService->getPastGamesWithPrizes($user->getId()));
        $pageInactives = (!empty($this->request->get('page'))) ? $this->request->get('page') : 1;
        $paginatorInactives = $this->getPaginatorAsArray(!empty($myGamesInactives->result['dates']) ? $myGamesInactives->result['dates'] : [], 4, $pageInactives);
        $paginatorViewInactives = (new PaginationWidget($paginatorInactives, $this->request->getQuery()))->render();

        $userBets = $this->reportsService->getAutomaticAndTicketPurchaseByUserId($this->request->get('id'));
        $userDeposits = $this->reportsService->getDepositsByUserId($this->request->get('id'));

        $this->view->setVars([
            'user' => $user,
            'my_games_actives' => $myGamesActives,
            'paginator_view_actives' => '', //$paginatorViewActives
            'my_games_inactives' => $paginatorInactives->getPaginate()->items,
            'paginator_view_inactives' => $paginatorViewInactives,
            'nextDrawDate' => $this->reportsService->getNextDateDrawByLottery('Euromillions')->format('Y M d'),
            'lottery' => 'Euromillions',
            'userBets' => $userBets,
            'userDeposits' => $userDeposits,
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

    private function getCountries()
    {
        $countries = $this->geoService->countryList();
        sort($countries);
        //key+1, select element from phalcon need index 0 to set empty value
        return array_combine(range(1, count($countries)), array_values($countries));
    }
}