<?php
namespace EuroMillions\web\controllers;

use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\components\ViewHelper;
use Phalcon\Di;

class IndexController  extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrency($this->lotteryService->getNextJackpot('EuroMillions'));
        $this->view->setVar('jackpot_value', ViewHelper::formatJackpotNoCents($jackpot));
        $time_till_next_draw = $this->lotteryService->getTimeToNextDraw('EuroMillions');
        $date_next_draw = $this->lotteryService->getNextDateDrawByLottery('EuroMillions');
        $last_draw_date = $this->lotteryService->getLastDrawDate('EuroMillions');
        $this->view->setVar('euromillions_results', $this->lotteryService->getLastResult('EuroMillions'));
        $this->view->setVar('days_till_next_draw', $time_till_next_draw->d);
        $this->view->setVar('hours_till_next_draw', $time_till_next_draw->h);
        $this->view->setVar('minutes_till_next_draw', $time_till_next_draw->i);
        $this->view->setVar('date_to_draw', $date_next_draw->format('Y-m-d H:i:s'));
        $this->view->setVar('last_draw_date', $last_draw_date->format('l, F j, Y'));
        $this->tag->prependTitle('Play Euromillions & Check the Results');
        MetaDescriptionTag::setDescription('Play EuroMillions online at the best price on the official website of EuroMillions.com! Play and check Euro millions results of Europe\'s biggest lottery.');
    }

    public function notfoundAction()
    {
        $this->response->redirect('/error/page404');
    }
}

