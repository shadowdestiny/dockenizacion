<?php

namespace EuroMillions\admin\controllers;

use EuroMillions\admin\services\TrackingService;
use EuroMillions\shared\components\widgets\PaginationWidgetAdmin;
use EuroMillions\shared\services\CurrencyConversionService;
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

    /** @var  $currencyConversionService CurrencyConversionService */
    protected $currencyConversionService;

    public function initialize()
    {
        parent::initialize();
        $this->trackingService = $this->domainAdminServiceFactory->getTrackingService();
        $this->geoService = $this->domainAdminServiceFactory->getGeoService();
        $this->currencyConversionService = $this->domainAdminServiceFactory->getCurrencyConversionService();
        $this->countries = $this->getCountries();

    }

    public function indexAction()
    {

    }

    public function businessReportsGeneralKPIsAction()
    {
        $this->checkPermissions();
        $this->view->setVars([
            'needReportsMenu' => true,
            'countryList' => $this->countries,
        ]);
    }

    public function businessReportsGeneralKPIsResultAction()
    {
        $this->checkPermissions();
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
        $this->checkPermissions();
        $this->view->setVars([
            'needReportsMenu' => true,
            'countryList' => $this->countries,
        ]);
    }

    public function businessReportsActivityResultAction()
    {
        $this->checkPermissions();
        $activities = [];
        if ($this->request->getPost()) {
            $activities = $this->reportsService->getActivities($this->request->getPost());
        }

        $this->view->pick('reports/results/_activityResults');
        $this->view->setVars([
            'countryList' => $this->countries,
            'generalKPIs' => $activities[0],
            'arrayDates' => $activities[1],
            'arrayTotals' => $activities[2],
            'total' => $activities[3],
            'valueTotalManualAlternativeQueryForOneValue' => $activities[4],
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
//            'playersGGRList' => $this->reportsService->getGGRPlayersByDates(
//                $this->request->getPost('check_ggr'),
//                $this->request->getPost('dateFrom'),
//                $this->request->getPost('dateTo')
//            ),
            'playersAcceptingEmailsList' => $this->reportsService->getAcceptingEmailsPlayers(),
            'countryList' => $this->countries,
        ]);
    }


    public function playerDetailsAction()
    {
        $user = $this->reportsService->getUserById($this->request->get('id'));

        $myGamesActives = new UpcomingDrawsDTO($this->reportsService->getActivePlaysByUserId($user->getId()),1);
        $pageActives = (!empty($this->request->get('pageActives'))) ? $this->request->get('pageActives') : 1;
        $paginatorActives = $this->getPaginatorAsArray(!empty($myGamesActives->result) ? $myGamesActives->result : [], 2, $pageActives);
        $paginatorViewActives = (new PaginationWidgetAdmin($paginatorActives, $this->request->getQuery(), [], 'pageActives'))->render();

        $mySubscriptionActives = $this->reportsService->getSubscriptionsByUserIdActive($user->getId(), $this->reportsService->getNextDateDrawByLottery('EuroMillions'), $this->reportsService->getNextDateDrawByLottery('PowerBall'), $this->reportsService->getNextDateDrawByLottery('MegaMillions'), $this->reportsService->getNextDateDrawByLottery('EuroJackpot'),  $this->reportsService->getNextDateDrawByLottery('MegaSena'));
        $pageSubsActives = (!empty($this->request->get('pageSubsActives'))) ? $this->request->get('pageSubsActives') : 1;
        $paginatorSubsActives = $this->getPaginatorAsArray(!empty($mySubscriptionActives) ? $mySubscriptionActives : [], 4, $pageSubsActives);
        $paginatorViewSubsActives = (new PaginationWidgetAdmin($paginatorSubsActives, $this->request->getQuery(), [], 'pageSubsActives'))->render();

        $mySubsInactives =  $this->reportsService->getSubscriptionsByUserIdInactive($user->getId());
        $pageSubsInactives = (!empty($this->request->get('pageSubsInactives'))) ? $this->request->get('pageSubsInactives') : 1;
        $paginatorSubsInactives = $this->getPaginatorAsArray(!empty($mySubsInactives) ? $mySubsInactives : [], 4, $pageSubsInactives);
        $paginatorViewSubsInactives = (new PaginationWidgetAdmin($paginatorSubsInactives, $this->request->getQuery(), [], 'pageSubsInactives'))->render();

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
            'my_subscription_actives' => $paginatorSubsActives->getPaginate()->items,
            'my_subscription_inactives' => $paginatorSubsInactives->getPaginate()->items,
            'my_games_actives' => $paginatorActives->getPaginate()->items,
            'paginator_view_actives' => $paginatorViewActives,
            'my_games_inactives' => $paginatorInactives->getPaginate()->items,
            'paginator_view_inactives' => $paginatorViewInactives,
            'my_christmas_actives' => $this->reportsService->getMyActiveChristmas($user->getId()),
            'userBets' => $paginatorBets->getPaginate()->items,
            'paginator_view_bets' => $paginatorViewBets,
            'userDeposits' => $paginatorDeposits->getPaginate()->items,
            'paginator_view_deposits' => $paginatorViewDeposits,
            'nextDrawDate' => $this->reportsService->getNextDateDrawByLottery('Euromillions')->format('Y M d'),
            'lottery' => 'Euromillions',
            'userWithdrawals' => $this->reportsService->getWithdrawalsByUserId($this->request->get('id')),
            'countryList' => $this->countries,
            'paginator_view_subs_inactive' => $paginatorViewSubsInactives,
            'paginator_view_subs_active' => $paginatorViewSubsActives,
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
                    fputcsv($fp, [$userDeposit['date'], $userDeposit['entity_type'], sprintf("%.2f", $userDeposit['movement'] / 100), sprintf("%.2f", $userDeposit['balance'] / 100)]);
                }
                fclose($fp);
            }
        }
    }

    public function salesDrawAction()
    {
        $this->checkPermissions();
        $this->view->setVars([
            'needReportsMenu' => true,
            'salesDraw' => $this->reportsService->fetchSalesDraw()
        ]);
    }

    public function salesDrawPowerBallAction()
    {
        $this->checkPermissions();
        $this->view->setVars([
            'needReportsMenu' => true,
            'salesDraw' => $this->reportsService->fetchSalesDrawPowerBall()
        ]);
    }

    public function salesDrawMegaMillionsAction()
    {
        $this->checkPermissions();
        $this->view->setVars([
            'needReportsMenu' => true,
            'salesDraw' => $this->reportsService->fetchSalesDrawMegaMillions()
        ]);
    }

    public function salesDrawEuroJackpotAction()
    {
        $this->checkPermissions();
        $this->view->setVars([
            'needReportsMenu' => true,
            'salesDraw' => $this->reportsService->fetchSalesDrawEuroJackpot()
        ]);
    }

    public function salesDrawChristmasAction()
    {
        $this->checkPermissions();
        $this->view->setVars([
            'needReportsMenu' => true,
            'salesDraw' => $this->reportsService->fetchSalesDrawChristmas()
        ]);
    }

    public function salesDrawMegaSenaAction()
    {
        $this->checkPermissions();

        $this->view->setVars([
            'needReportsMenu' => false,
            'salesDraw' => $this->reportsService->fetchSalesDrawMegaSena()
        ]);
    }

    public function salesDrawDetailsAction()
    {
        $this->checkPermissions();
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

    public function salesDrawPowerBallDetailsAction()
    {
        $this->checkPermissions();
        if ($this->request->get('id')) {
            $drawDates = $this->reportsService->getPowerBallDrawsActualAfterDatesById($this->request->get('id'));

            $this->view->setVars([
                'needReportsMenu' => true,
                'euromillionsDrawId' => $this->request->get('id'),
                'salesDrawDetailsData' => $this->reportsService->getPowerBallDrawDetailsByIdAndDates($this->request->get('id'), $drawDates),
                'countryList' => $this->countries,
            ]);

        }
    }

    public function salesDrawMegaMillionsDetailsAction()
    {
        $this->checkPermissions();
        if ($this->request->get('id')) {
            $drawDates = $this->reportsService->getMegaMillionsDrawsActualAfterDatesById($this->request->get('id'));

            $this->view->setVars([
                'needReportsMenu' => true,
                'euromillionsDrawId' => $this->request->get('id'),
                'salesDrawDetailsData' => $this->reportsService->getMegaMillionsDrawDetailsByIdAndDates($this->request->get('id'), $drawDates),
                'countryList' => $this->countries,
            ]);

        }
    }

    public function salesDrawMegaSenaDetailsAction()
    {
        $this->checkPermissions();
        if ($this->request->get('id')) {
            $drawDates = $this->reportsService->getMegaSenaDrawsActualAfterDatesById($this->request->get('id'));
            $this->view->setVars([
                'needReportsMenu' => true,
                'euromillionsDrawId' => $this->request->get('id'),
                'salesDrawDetailsData' => $this->reportsService->getMegaSenaDrawDetailsByIdAndDates($this->request->get('id'), $drawDates),
                'countryList' => $this->countries,
            ]);

        }
    }

    public function salesDrawChristmasDetailsAction()
    {
        $this->checkPermissions();
        if ($this->request->get('id')) {
            $drawDates = $this->reportsService->getChristmasDrawsActualAfterDatesById($this->request->get('id'));
            $this->view->setVars([
                'needReportsMenu' => true,
                'euromillionsDrawId' => $this->request->get('id'),
                'salesDrawDetailsData' => $this->reportsService->getChristmasDrawDetailsByIdAndDates($this->request->get('id'), $drawDates),
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

    /**
     * @return mixed
     */
    public function saveDisabledUserAction()
    {
        return $this->reportsService->saveDisabledUser($this->request->getPost('userId'), $this->request->getPost('isChecked'), $this->request->getPost('userDate'));
    }

    private function getCountries()
    {
        $countries = $this->geoService->countryList();
        sort($countries);
        //key+1, select element from phalcon need index 0 to set empty value
        return array_combine(range(1, count($countries)), array_values($countries));
    }

    private function checkPermissions()
    {
        if (strpos('S', $this->session->get('userAdminAccess'))  !== false) {
            return $this->response->redirect('/admin/index/notaccess');
        }
    }
}