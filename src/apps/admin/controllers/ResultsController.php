<?php

namespace EuroMillions\admin\controllers;

use EuroMillions\admin\services\UserAdminService;

class ResultsController extends ApiControllerBase
{

    /** @var UserAdminService $lotteryDataService */
    private $lotteryDataService;

    public function initialize()
    {
        parent::initialize();
        $this->lotteryDataService = $this->domainAdminServiceFactory->getLotteriesDataService();
    }

    public function euromillionsAction($date = null)
    { 
        return $this->handleLottery('EuroMillions', $date);
    }

    public function powerballAction($date = null)
    { 
        return $this->handleLottery('PowerBall', $date);
    }

    public function megamillionsAction($date = null)
    { 
        return $this->handleLottery('MegaMillions', $date);
    }

    public function eurojackpotAction($date = null)
    { 
        $data = [];

        if ($this->request->isPost()) {
            $data = [
                'numbers' => [
                    'main' => [
                        $this->request->getPost('regular_number_one'),
                        $this->request->getPost('regular_number_two'),
                        $this->request->getPost('regular_number_three'),
                        $this->request->getPost('regular_number_four'),
                        $this->request->getPost('regular_number_five'),
                    ],
                    'lucky' => [
                        $this->request->getPost('lucky_number_one'),
                        $this->request->getPost('lucky_number_two'),
                    ]
                ],
                'jackpot' => $this->request->getPost('jackpot'),
                'breakdown' => [
                    'winners' => [
                        'match-2' => $this->request->getPost('win_category_thirteen'),
                        'match-2-1' => $this->request->getPost('win_category_twelve'),
                        'match-1-2' => $this->request->getPost('win_category_eleven'),
                        'match-2-2' => $this->request->getPost('win_category_ten'),
                        'match-3' => $this->request->getPost('win_category_nine'),
                        'match-3-1' => $this->request->getPost('win_category_eight'),
                        'match-3-2' => $this->request->getPost('win_category_seven'),
                        'match-4' => $this->request->getPost('win_category_six'),
                        'match-4-1' => $this->request->getPost('win_category_five'),
                        'match-4-2' => $this->request->getPost('win_category_four'),
                        'match-5' => $this->request->getPost('win_category_three'),
                        'match-5-1' => $this->request->getPost('win_category_two'),
                        'match-5-2' => $this->request->getPost('win_category_one'),
                    ],
                    'prizes' => [
                        'match-2' => $this->request->getPost('prize_category_thirteen'),
                        'match-2-1' => $this->request->getPost('prize_category_twelve'),
                        'match-1-2' => $this->request->getPost('prize_category_eleven'),
                        'match-2-2' => $this->request->getPost('prize_category_ten'),
                        'match-3' => $this->request->getPost('prize_category_nine'),
                        'match-3-1' => $this->request->getPost('prize_category_eight'),
                        'match-3-2' => $this->request->getPost('prize_category_seven'),
                        'match-4' => $this->request->getPost('prize_category_six'),
                        'match-4-1' => $this->request->getPost('prize_category_five'),
                        'match-4-2' => $this->request->getPost('prize_category_four'),
                        'match-5' => $this->request->getPost('prize_category_three'),
                        'match-5-1' => $this->request->getPost('prize_category_two'),
                        'match-5-2' => $this->request->getPost('prize_category_one'),
                    ]
                ]
            ];
            
        }

        return $this->handleLottery('EuroJackpot', $date, $data);
    }

    public function megasenaAction($date = null)
    { 
        return $this->handleLottery('MegaSena', $date);
    }

    public function superenalottoAction($date = null)
    { 
        return $this->handleLottery('SuperEnalotto', $date);
    }

    protected function handleLottery($lottery, $date, $data)
    {
        if ($this->request->isGet()) {
            return $this->getResults($lottery, $date);
        }

        if ($this->request->isPost()) {
            return $this->createOrUpdateResults($lottery, $date, $data);
        }

        if ($this->request->isDelete()) {
            return $this->deleteResults($lottery, $date);
        }
    }

    protected function getResults($lottery, $date)
    {
        $result = $date ? $this->lotteryDataService->getResultsByDate($lottery, \DateTime::createFromFormat('Y-m-d', $date))
            : $this->lotteryDataService->getResults($lottery);

        if($result->success()) {
            return $this->sendJson($result->getValues());
        }

        return $this->sendError($result->errorMessage());
    }

    protected function createOrUpdateResults($lottery, $date, $data)
    {
        $dateDraw = $date ?
            \DateTime::createFromFormat('Y-m-d', $date) :
            \DateTime::createFromFormat('Y-m-d', $this->request->getPost('date'));

        $result = $date ?
            $this->lotteryDataService->updateDrawResults($lottery, $dateDraw, $data) :
            $this->lotteryDataService->createDrawResults($lottery, $dateDraw, $data);

        if($result->success()) {
            return $this->sendJson($result->getValues());
        }

        return $this->sendError($result->errorMessage());
    }

    protected function deleteResults($lottery, $date)
    {
        if (is_null($date)) {
            return $this->sendError('Invalid date');
        }

        $result = $this->lotteryDataService->deleteDrawResults($lottery, \DateTime::createFromFormat('Y-m-d', $date));

        if($result->success()) {
            return $this->sendJson($result->getValues());
        }

        return $this->sendError($result->errorMessage());
    }
}