<?php

namespace EuroMillions\web\controllers;

use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\components\ViewHelper;

class IndexController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot('EuroMillions'));
        $this->view->setVar('jackpot_value', ViewHelper::formatJackpotNoCents($jackpot));
        $time_till_next_draw = $this->lotteryService->getTimeToNextDraw('EuroMillions');
        $date_next_draw = $this->lotteryService->getNextDateDrawByLottery('EuroMillions');
        $last_draw_date = $this->lotteryService->getLastDrawDate('EuroMillions');
        $this->view->setVar('euromillions_results', $this->lotteryService->getLastResult('EuroMillions'));
        $this->view->setVar('days_till_next_draw', $time_till_next_draw->d);
        $this->view->setVar('hours_till_next_draw', $time_till_next_draw->h);
        $this->view->setVar('minutes_till_next_draw', $time_till_next_draw->i);
        $this->view->setVar('date_to_draw', $date_next_draw->format('Y-m-d H:i:s'));
        $this->view->setVar('date_draw', $this->lotteryService->getNextDateDrawByLottery('EuroMillions')->modify('-1 hours')->format('Y-m-d H:i:s'));
        $this->view->setVar('last_draw_date', $last_draw_date->format('l, F j, Y'));
        $this->view->setVar('pageController', 'index');

        $this->tag->prependTitle($this->languageService->translate('home_name') . ViewHelper::formatJackpotNoCents($jackpot));
        MetaDescriptionTag::setDescription($this->languageService->translate('home_desc'));
    }

    public function notfoundAction()
    {
        $this->response->redirect('/error/page404');
    }
}

