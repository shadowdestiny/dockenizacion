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
        $lotteryName = 'EuroMillions';
        $now = new \DateTime();
        $draw_result = $this->lotteryService->getBreakDownDrawByDate($lotteryName,$now);
        $date_next_draw = $this->lotteryService->getNextDateDrawByLottery('EuroMillions');
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrency($this->lotteryService->getNextJackpot('EuroMillions'));
        if($draw_result->success()) {
            $breakDownDTO = new EuroMillionsDrawBreakDownDTO($draw_result->getValues()->getBreakDown());
            $break_down_list = $this->convertCurrency($breakDownDTO->toArray());
        }
        $last_result = $this->lotteryService->getLastResult($lotteryName);
        $currency_symbol = $this->userPreferencesService->getMyCurrencyNameAndSymbol();
        $last_draw_date = $this->lotteryService->getLastDrawDate($lotteryName);

        return $this->view->setVars([
            'break_downs' => !empty($break_down_list) ? $break_down_list : '',
            'id_draw' => !empty($draw_result) ? $draw_result->getValues()->getId() : '',
            'jackpot_value' => $jackpot->getAmount()/100,
            'last_result' => $last_result,
            'date_draw' => $date_next_draw->format('Y-m-d H:i:s'),
            'last_draw_date' => $last_draw_date->format('D, d M Y'),
            'symbol' => $currency_symbol['symbol']
        ]);
    }

    private function convertCurrency(array $break_downs)
    {
        $user_currency = $this->userPreferencesService->getCurrency();
        if(!empty($break_downs)) {
            foreach($break_downs as &$breakDown) {
                $breakDown['lottery_prize'] = $this->currencyConversionService->convert(new Money((int) $breakDown['lottery_prize'], new Currency('EUR')), $user_currency)->getAmount() / 10000;
            }
        }
        return $break_downs;
    }

}