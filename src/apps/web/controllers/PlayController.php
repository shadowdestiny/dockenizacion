<?php
namespace EuroMillions\web\controllers;

use EuroMillions\web\components\DateTimeUtil;
use Money\Currency;
use Money\Money;

class PlayController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        //EMTD surely we need more things
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrency($this->lotteriesDataService->getNextJackpot('EuroMillions'));
        //EMTD @rmrbest The private method you're using belongs to a service, and it should be created by TDD. The way you did it is cheating. Please, fix it asap.
        $play_dates = $this->nextDrawsRecurrent();

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

    private function nextDrawsRecurrent($maxIteration = 5)
    {
        $drawDates = [];

        for($i=0; $i < $maxIteration; $i++){
            if($i == 0) $lastDraw = new \DateTime(date("Y-m-d H:i:s"));
            $lastDraw = $this->lotteriesDataService->getNextDateDrawByLottery('EuroMillions',$lastDraw);
            $drawDates[] = [date('w',$lastDraw->getTimestamp()) => $lastDraw->format('d M Y')];
        }
        return $drawDates;
    }
}
