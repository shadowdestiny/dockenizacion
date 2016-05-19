<?php


namespace EuroMillions\web\controllers;


use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use Money\Currency;
use Money\Money;


class NumbersController extends PublicSiteControllerBase
{

    public function indexAction()
    {
        $date = $this->request->get('date');
        $lotteryName = 'EuroMillions';
        $date = empty($date) ? new \DateTime() : new \DateTime($date);
        $result = $this->lotteryService->getDrawsDTO($lotteryName);
        $draw_result = $this->lotteryService->getDrawWithBreakDownByDate($lotteryName,$date);
        if(!$result->success()) {
            return $this->view->setVars([
               'error' => $result->errorMessage()
            ]);
        }
        /** @var EuroMillionsDraw $euroMillionsDraw */
        $euroMillionsDraw = $draw_result->getValues();
        $breakDownDTO = new EuroMillionsDrawBreakDownDTO($euroMillionsDraw->getBreakDown());
        $break_down_list = $this->convertCurrency($breakDownDTO->toArray());
        return $this->view->setVars([
            'break_downs' => !empty($break_down_list) ? $break_down_list : '',
            'id_draw' => $euroMillionsDraw->getId(),
            'jackpot_value' =>$this->userPreferencesService->getJackpotInMyCurrency($this->lotteryService->getNextJackpot('EuroMillions')),
            'last_result' => ['regular_numbers' => $euroMillionsDraw->getResult()->getRegularNumbersArray(), 'lucky_numbers' => $euroMillionsDraw->getResult()->getLuckyNumbersArray() ],
            'date_draw' =>  $this->lotteryService->getNextDateDrawByLottery('EuroMillions')->format('Y-m-d H:i:s'),
            'last_draw_date' => $euroMillionsDraw->getDrawDate()->format('D, d M Y'),
            'symbol' => $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'],
            'list_draws' => $result->getValues(),
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