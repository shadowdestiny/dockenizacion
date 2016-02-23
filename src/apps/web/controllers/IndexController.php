<?php
namespace EuroMillions\web\controllers;
use Phalcon\Di;

class IndexController  extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrency($this->lotteriesDataService->getNextJackpot('EuroMillions'));
        $this->view->setVar('jackpot_value', $jackpot->getAmount()/100);
        $time_till_next_draw = $this->lotteriesDataService->getTimeToNextDraw('EuroMillions');
        $date_next_draw = $this->lotteriesDataService->getNextDateDrawByLottery('EuroMillions');
        $last_draw_date = $this->lotteriesDataService->getLastDrawDate('EuroMillions');
        $this->view->setVar('euromillions_results', $this->lotteriesDataService->getLastResult('EuroMillions'));
        $this->view->setVar('days_till_next_draw', $time_till_next_draw->d);
        $this->view->setVar('hours_till_next_draw', $time_till_next_draw->h);
        $this->view->setVar('minutes_till_next_draw', $time_till_next_draw->i);
        $this->view->setVar('date_to_draw', $date_next_draw->format('Y-m-d H:i:s'));
        $this->view->setVar('last_draw_date', $last_draw_date->format('l, F j, Y'));

    }

    public function notfoundAction(\Exception $exception = null)
    {
        $this->noRender();
        echo 'ERROR 404: ' . $exception->getMessage();
    }
}

