<?php


namespace EuroMillions\controllers;


use EuroMillions\vo\dto\EuroMillionsDrawBreakDownDTO;
use Phalcon\Mvc\View\Engine\Volt;

class NumbersController extends PublicSiteControllerBase
{

    public function indexAction()
    {
        //EMTD surely we need send to view more vars
        $lotteryName = 'EuroMillions';
        $now = new \DateTime();
        $breakDown = $this->lotteriesDataService->getBreakDownDrawByDate($lotteryName,$now);
        $jackpot = $this->userService->getJackpotInMyCurrency($this->lotteriesDataService->getNextJackpot('EuroMillions'));
        $breakDownDTO = new EuroMillionsDrawBreakDownDTO($breakDown->getValues());
        return $this->view->setVars([
            'break_downs' => $breakDownDTO->toArray(),
            'jackpot_value' => $jackpot->getAmount()/100
        ]);
    }

}