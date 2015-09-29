<?php
namespace EuroMillions\controllers;

use Money\Currency;
use Money\Money;

class PlayController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        //EMTD surely we need more things
        $jackpot = $this->userService->getJackpotInMyCurrency($this->lotteriesDataService->getNextJackpot('EuroMillions'));
        $play_dates = $this->nextDrawsRecurrent();
        $dayOfWeek = function() {
            $draw = $this->lotteriesDataService->getNextDrawByLottery('EuroMillions');
            return (int) date('w',$draw->getTimestamp());
        };

        $result = $this->lotteriesDataService->getLotteryConfigByName('EuroMillions');
        $single_bet_price = new Money(0, new Currency('EUR'));
        if($result->success()){
            $single_bet_price = $result->getValues()->getSingleBetPrice();

        }

        return $this->view->setVars([
            'jackpot_value' => $jackpot->getAmount()/100,
            'play_dates' => $play_dates,
            'next_draw' => $dayOfWeek(),
            'single_bet_price' => $single_bet_price->getAmount(),
        ]);
    }

    private function nextDrawsRecurrent($maxIteration = 5)
    {
        $drawDates = [];

        for($i=0; $i < $maxIteration; $i++){
            if($i == 0) $lastDraw = new \DateTime(date("Y-m-d H:i:s"));
            $lastDraw = $this->lotteriesDataService->getNextDrawByLottery('EuroMillions',$lastDraw);
            $drawDates[] = [date('w',$lastDraw->getTimestamp()) => $lastDraw->format('Y-m-d')];
        }
        return $drawDates;
    }
}