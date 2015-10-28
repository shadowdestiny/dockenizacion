<?php


namespace EuroMillions\web\controllers;


use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
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
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrency($this->lotteriesDataService->getNextJackpot('EuroMillions'));
        $breakDownDTO = new EuroMillionsDrawBreakDownDTO($breakDown->getValues());

        $break_down_list = $this->convertCurrency($breakDownDTO->toArray());
        return $this->view->setVars([
            'break_downs' => $break_down_list,
            'jackpot_value' => $jackpot->getAmount()/100
        ]);
    }

    private function convertCurrency(array $break_downs)
    {
        $user_currency = $this->userPreferencesService->getCurrency();
        if(!empty($break_downs)) {
            foreach($break_downs as &$breakDown) {
                $breakDown['lottery_prize'] = $this->currencyService->convert(new Money((int) $breakDown['lottery_prize'], new Currency('EUR')), $user_currency)->getAmount() / 10000;
            }
        }
        return $break_downs;
    }

}