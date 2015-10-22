<?php


namespace EuroMillions\controllers;


use EuroMillions\vo\dto\EuroMillionsDrawBreakDownDTO;
use Money\Currency;
use Money\Money;
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
        $breakDownDTO = new EuroMillionsDrawBreakDownDTO($breakDown->getValues(),$this->currencyService);

        $break_down_list = $breakDownDTO->convertCurrency($this->userService->getCurrency());
        return $this->view->setVars([
            'break_downs' => $break_down_list,
            'jackpot_value' => $jackpot->getAmount()/100
        ]);
    }
}