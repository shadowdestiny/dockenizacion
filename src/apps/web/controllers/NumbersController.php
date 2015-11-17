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
        $date_next_draw = $this->lotteriesDataService->getNextDateDrawByLottery('EuroMillions');
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrency($this->lotteriesDataService->getNextJackpot('EuroMillions'));
        $breakDownDTO = new EuroMillionsDrawBreakDownDTO($breakDown->getValues());        
        $break_down_list = $this->convertCurrency($breakDownDTO->toArray());
        $last_result = $this->lotteriesDataService->getLastResult($lotteryName);
        $last_draw_date = $this->lotteriesDataService->getLastDrawDate($lotteryName);

        return $this->view->setVars([
            'break_downs' => $break_down_list,
            'jackpot_value' => $jackpot->getAmount()/100,
            'last_result' => $last_result,
            'date_draw' => $date_next_draw->format('Y-m-d H:i:s'),
            'last_draw_date' => $last_draw_date->format('D, d M Y')
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