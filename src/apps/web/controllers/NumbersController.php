<?php


namespace EuroMillions\web\controllers;


use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use EuroMillions\web\vo\dto\EuroMillionsDrawDTO;
use Money\Currency;
use Money\Money;


class NumbersController extends PublicSiteControllerBase
{

    public function indexAction()
    {
        $lotteryName = 'EuroMillions';
        $now = new \DateTime();
        $result = $this->lotteryService->getDrawsDTO($lotteryName);
        if(!$result->success()) {
            return $this->view->setVars([
               'error' => $result->errorMessage()
            ]);
        }
        /** @var EuroMillionsDrawDTO $euroMillionsDraw */
        $euroMillionsDraw = $result->getValues()[1];
        $break_down_list = $this->convertCurrency($euroMillionsDraw->euroMillionsDrawBreakDownDTO->toArray());
        return $this->view->setVars([
            'break_downs' => !empty($break_down_list) ? $break_down_list : '',
            'id_draw' => $euroMillionsDraw->id,
            'jackpot_value' =>$this->userPreferencesService->getJackpotInMyCurrency($this->lotteryService->getNextJackpot('EuroMillions')),
            'last_result' => ['regular_numbers' => $euroMillionsDraw->regularNumbersArray, 'lucky_numbers' => $euroMillionsDraw->luckyNumbersArray ],
            'date_draw' =>  $this->lotteryService->getNextDateDrawByLottery('EuroMillions')->format('Y-m-d H:i:s'),
            'last_draw_date' => $euroMillionsDraw->drawDate->format('D, d M Y'),
            'symbol' => $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol']
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