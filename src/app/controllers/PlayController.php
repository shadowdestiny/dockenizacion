<?php
namespace EuroMillions\controllers;

class PlayController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        //EMTD surely we need more things
        $jackpot = $this->userService->getJackpotInMyCurrency($this->lotteriesDataService->getNextJackpot('EuroMillions'));
        $play_dates = $this->nextDrawsRecurrent();
        return $this->view->setVars([
            'jackpot_value' => $jackpot->getAmount()/100,
            'play_dates' => $play_dates,
            'next_draw' => $this->getDayOfWeek()
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

    private function getDayOfWeek()
    {
        $draw = $this->lotteriesDataService->getNextDrawByLottery('EuroMillions');
        return date('w',$draw->getTimestamp());
    }

}