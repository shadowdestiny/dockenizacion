<?php

namespace EuroMillions\admin\controllers;

use EuroMillions\admin\services\UserAdminService;

class PrizesController extends ApiControllerBase
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
        return $this->handleLottery('EuroJackpot', $date);
    }

    public function megasenaAction($date = null)
    { 
        return $this->handleLottery('MegaSena', $date);
    }

    public function superenalottoAction($date = null)
    { 
        return $this->handleLottery('SuperEnalotto', $date);
    }

    protected function handleLottery($lottery, $date)
    {
        if ($this->request->isGet()) {
            return $this->getPrizes($lottery, $date);
        }

        if ($this->request->isPost()) {
            return $this->createOrUpdatePrizes($lottery, $date);
        }

        if ($this->request->isDelete()) {
            return $this->deletePrizes($lottery, $date);
        }
    }

    protected function getPrizes($lottery, $date)
    {
        $result = $date ? $this->lotteryDataService->getPrizesByDate($lottery, \DateTime::createFromFormat('Y-m-d', $date))
            : $this->lotteryDataService->getPrizes($lottery);

        if ($result->success()) {
            return $this->sendJson($result->getValues());
        }

        return $this->sendError($result->errorMessage());
    }

    protected function createOrUpdatePrizes($lottery, $date)
    {
        $numbers = [
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
        ];

        $dateDraw = $date ?
            \DateTime::createFromFormat('Y-m-d', $date) :
            \DateTime::createFromFormat('Y-m-d', $this->request->getPost('date'));

        $result = $date ?
            $this->lotteryDataService->updateDrawResults($lottery, $dateDraw, $numbers) :
            $this->lotteryDataService->createDrawResults($lottery, $dateDraw, $numbers);

        if($result->success()) {
            return $this->sendJson($result->getValues());
        }

        return $this->sendError($result->errorMessage());
    }

    protected function deletePrizes($lottery, $date)
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