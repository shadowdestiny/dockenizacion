<?php

namespace EuroMillions\admin\controllers;

use EuroMillions\admin\services\TrackingService;
use EuroMillions\shared\components\widgets\PaginationWidgetAdmin;
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
            'countryList' => $this->countries,
        ]);
    }

    public function businessReportsGeneralKPIsResultAction()
    {
        $generalKPIs = [];
        if ($this->request->getPost()) {
            $generalKPIs = $this->reportsService->getGeneralKPI($this->request->getPost());
        }

        $this->view->pick('reports/results/_generalKPIsResult');
        $this->view->setVars([
            'countryList' => $this->countries,
            'generalKPIs' => $generalKPIs[0],
            'arrayDates' => $generalKPIs[1],
            'arrayTotals' => $generalKPIs[2],
            'total' => $generalKPIs[3],
            'valueTotalManualAlternativeQueryForOneValue' => $generalKPIs[4],
        ]);
    }

    public function businessReportsActivityAction()
    {
        $this->view->setVars([
            'needReportsMenu' => true,
            'countryList' => $this->countries,
        ]);
    }

    public function businessReportsActivityResultAction()
    {
        $generalKPIs = [];
        if ($this->request->getPost()) {
            $generalKPIs = $this->reportsService->getGeneralKPI($this->request->getPost());
        }

        $this->view->pick('reports/results/_generalKPIsResult');
        $this->view->setVars([
            'countryList' => $this->countries,
            'generalKPIs' => $generalKPIs[0],
            'arrayDates' => $generalKPIs[1],
            'arrayTotals' => $generalKPIs[2],
            'total' => $generalKPIs[3],
            'valueTotalManualAlternativeQueryForOneValue' => $generalKPIs[4],
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
        $pageActives = (!empty($this->request->get('pageActives'))) ? $this->request->get('pageActives') : 1;
        $paginatorActives = $this->getPaginatorAsArray(!empty($myGamesActives->result) ? $myGamesActives->result : [], 4, $pageActives);
        $paginatorViewActives = (new PaginationWidgetAdmin($paginatorActives, $this->request->getQuery(), [], 'pageActives'))->render();

        $myGamesInactives = new PastDrawsCollectionDTO($this->reportsService->getPastGamesWithPrizes($user->getId()));
        $pageInactives = (!empty($this->request->get('pageInactives'))) ? $this->request->get('pageInactives') : 1;
        $paginatorInactives = $this->getPaginatorAsArray(!empty($myGamesInactives->result['dates']) ? $myGamesInactives->result['dates'] : [], 4, $pageInactives);
        $paginatorViewInactives = (new PaginationWidgetAdmin($paginatorInactives, $this->request->getQuery(), [], 'pageInactives'))->render();

        $userBets = $this->reportsService->getAutomaticAndTicketPurchaseByUserId($this->request->get('id'));
        $pageBets = (!empty($this->request->get('pageBets'))) ? $this->request->get('pageBets') : 1;
        $paginatorBets = $this->getPaginatorAsArray(!empty($userBets) ? $userBets : [], 8, $pageBets);
        $paginatorViewBets = (new PaginationWidgetAdmin($paginatorBets, $this->request->getQuery(), [], 'pageBets'))->render();

        $userDeposits = $this->reportsService->getDepositsByUserId($this->request->get('id'));
        $pageDeposits = (!empty($this->request->get('pageDeposits'))) ? $this->request->get('pageDeposits') : 1;
        $paginatorDeposits = $this->getPaginatorAsArray(!empty($userDeposits) ? $userDeposits : [], 8, $pageDeposits);
        $paginatorViewDeposits = (new PaginationWidgetAdmin($paginatorDeposits, $this->request->getQuery(), [], 'pageDeposits'))->render();

        $this->view->setVars([
            'needReportsMenu' => true,
            'user' => $user,
            'my_games_actives' => $paginatorActives->getPaginate()->items,
            'paginator_view_actives' => $paginatorViewActives,
            'my_games_inactives' => $paginatorInactives->getPaginate()->items,
            'paginator_view_inactives' => $paginatorViewInactives,
            'userBets' => $paginatorBets->getPaginate()->items,
            'paginator_view_bets' => $paginatorViewBets,
            'userDeposits' => $paginatorDeposits->getPaginate()->items,
            'paginator_view_deposits' => $paginatorViewDeposits,
            'nextDrawDate' => $this->reportsService->getNextDateDrawByLottery('Euromillions')->format('Y M d'),
            'lottery' => 'Euromillions',
            'userWithdrawals' => $this->reportsService->getWithdrawalsByUserId($this->request->get('id')),
            'countryList' => $this->countries,
        ]);
    }

    public function downloadBetsAction()
    {
        if ($this->request->get('id')) {
            $userBets = $this->reportsService->getAutomaticAndTicketPurchaseByUserId($this->request->get('id'));
            if (count($userBets) > 0) {
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=userBets.csv');
                $fp = fopen('php://output', 'w');
                foreach ($userBets as $userBet) {
                    fputcsv($fp, [$userBet['date'], $userBet['entity_type'], sprintf("%.2f", $userBet['movement']), sprintf("%.2f", $userBet['balance'] / 100)]);
                }
                fclose($fp);
            }
        }
    }

    public function downloadDepositsAction()
    {
        if ($this->request->get('id')) {
            $userDeposits = $this->reportsService->getDepositsByUserId($this->request->get('id'));
            if (count($userDeposits) > 0) {
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=userDeposits.csv');
                $fp = fopen('php://output', 'w');
                foreach ($userDeposits as $userDeposit) {
                    if ($userDeposit['entity_type'] == 'subscription_purchase') {
                        $movement = $userDeposit['subsMovement'];
                    } else {
                        $movement = $userDeposit['movement'];
                    }

                    fputcsv($fp, [$userDeposit['date'], $userDeposit['entity_type'], sprintf("%.2f", $movement / 100), sprintf("%.2f", $userDeposit['balance'] / 100)]);
                }
                fclose($fp);
            }
        }
    }

    public function salesDrawAction()
    {
        $this->view->setVars([
            'needReportsMenu' => true,
            'salesDraw' => $this->reportsService->fetchSalesDraw()
        ]);
    }

    public function salesDrawDetailsAction()
    {
        if ($this->request->get('id')) {
            $drawDates = $this->reportsService->getEuromillionsDrawsActualAfterDatesById($this->request->get('id'));
            $this->view->setVars([
                'needReportsMenu' => true,
                'euromillionsDrawId' => $this->request->get('id'),
                'salesDrawDetailsData' => $this->reportsService->getEuromillionsDrawDetailsByIdAndDates($this->request->get('id'), $drawDates),
                'countryList' => $this->countries,
            ]);
        }
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