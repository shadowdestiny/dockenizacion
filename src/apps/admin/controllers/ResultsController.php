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

    /**
     * @return \Phalcon\Mvc\View
     */
    public function megasenaAction($date = null)
    {
        if ($this->request->isGet()) {
            return $this->getResults('MegaSena', $date);
        }

        if ($this->request->isPost()) {
            return $this->createOrUpdateResults('MegaSena', $date);
        }

        if ($this->request->isDelete()) {
            return $this->deleteResults('MegaSena', $date);
        }
    }

    protected function getResults($lottery, $date = null)
    {
        $result = $date ? $this->lotteryDataService->getResultsByDate($lottery, \DateTime::createFromFormat('Y-m-d', $date))
            : $this->lotteryDataService->getResults($lottery);

        if($result->success()) {
            return $this->sendJson($result->getValues());
        }

        return $this->sendError($result->errorMessage());
    }

    protected function createOrUpdateResults($lottery, $date = null)
    {
        $result = $date ? $this->lotteryDataService->getResultsByDate($lottery, \DateTime::createFromFormat('Y-m-d', $date))
            : $this->lotteryDataService->getResults($lottery);

        if($result->success()) {
            return $this->sendJson($result->getValues());
        }

        return $this->sendError($result->errorMessage());
    }

    protected function deleteResults($lottery, $date = null)
    {
        $result = $date ? $this->lotteryDataService->getResultsByDate($lottery, \DateTime::createFromFormat('Y-m-d', $date))
            : $this->lotteryDataService->getResults($lottery);

        if($result->success()) {
            return $this->sendJson($result->getValues());
        }

        return $this->sendError($result->errorMessage());
    }
}