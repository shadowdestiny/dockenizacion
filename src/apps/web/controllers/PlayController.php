<?php
namespace EuroMillions\web\controllers;

use EuroMillions\web\components\DateTimeUtil;
use Money\Currency;
use Money\Money;

class PlayController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrency($this->lotteriesDataService->getNextJackpot('EuroMillions'));
        $play_dates = $this->lotteriesDataService->getRecurrentDrawDates('EuroMillions');
        $draw = $this->lotteriesDataService->getNextDateDrawByLottery('EuroMillions');
        $date_time_util = new DateTimeUtil();
        $dayOfWeek = $date_time_util->getDayOfWeek($draw);
        $single_bet_price = $this->lotteriesDataService->getSingleBetPriceByLottery('EuroMillions');
        return $this->view->setVars([
            'jackpot_value' => $jackpot->getAmount()/100,
            'play_dates' => $play_dates,
            'next_draw' => $dayOfWeek,
            'single_bet_price' => $single_bet_price->getAmount()/10000,
        ]);
    }
}
