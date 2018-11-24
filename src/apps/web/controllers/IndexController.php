<?php

namespace EuroMillions\web\controllers;

use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\components\ViewHelper;
use EuroMillions\web\vo\dto\MainJackpotHomeDTO;

class IndexController extends PublicSiteControllerBase
{
    public function indexAction()
    {

        /** @var MainJackpotHomeDTO $mainJackpotHomeDTO */
        $mainJackpotHomeDTO = $this->lotteryService->mainJackpotHome();
        $euroMillionsDrawJackpotArr = $this->lotteryService->sliderAndBarJackpotHome();
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($mainJackpotHomeDTO->jackpot);
        $jackpotPowerBall = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot('PowerBall'));
        $jackpotChristmas = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot('Christmas'));
//        $this->view->setVar('jackpot_value', ViewHelper::formatJackpotNoCents($mainJackpotHomeDTO->jackpotAmount));
//        var_dump(ViewHelper::formatJackpotNoCents($jackpot)); die();
        $this->view->setVar('day_draw_christmas', $this->lotteryService->getNextDateDrawByLottery('Christmas')->format('l'));
        $this->view->setVar('next_draw_christmas', $this->lotteryService->getNextDateDrawByLottery('Christmas')->format('d.m.Y'));
        $textMillions = $this->billionsAndTrillions($jackpot, strtolower($mainJackpotHomeDTO->lotteryName));
        $textMillionsChristmas = $this->billionsAndTrillions($jackpotChristmas, 'christmas');
        $this->view->setVar('jackpot_millions', ViewHelper::formatMillionsJackpot($jackpot));
        $this->view->setVar('jackpot_powerball', ViewHelper::formatMillionsJackpot($jackpotPowerBall));
        $this->view->setVar('jackpot_christmas', ViewHelper::formatBillionsJackpot($jackpotChristmas, $this->languageService->getLocale()));
        $time_till_next_draw = $this->lotteryService->getTimeToNextDraw('EuroMillions');
        $date_next_draw = $this->lotteryService->getNextDateDrawByLottery('EuroMillions');
        $last_draw_date = $this->lotteryService->getLastDrawDate('EuroMillions');
        $this->view->setVar('language', $this->languageService->getLocale());
        $this->view->setVar('euromillions_last_result', $this->lotteryService->getLastResult('EuroMillions'));
        $this->view->setVar('euromillions_results', $this->lotteryService->getLastFiveResults('EuroMillions'));
        $this->view->setVar('days_till_next_draw', $time_till_next_draw->d);
        $this->view->setVar('hours_till_next_draw', $time_till_next_draw->h);
        $this->view->setVar('minutes_till_next_draw', $time_till_next_draw->i);
        $this->view->setVar('date_to_draw', $date_next_draw->format('Y-m-d H:i:s'));
        $this->view->setVar('date_draw', $mainJackpotHomeDTO->drawDate->modify('-1 hours')->format('Y-m-d H:i:s'));//$this->lotteryService->getNextDateDrawByLottery('EuroMillions')->modify('-1 hours')->format('Y-m-d H:i:s'));
        $this->view->setVar('date_draw_power', $this->lotteryService->getNextDateDrawByLottery('PowerBall')->modify('-1 hours')->format('Y-m-d H:i:s'));
        $this->view->setVar('last_draw_date', $last_draw_date->format('l, F j, Y'));
        $this->view->setVar('show_s_days', (new \DateTime())->diff($this->lotteryService->getNextDateDrawByLottery('EuroMillions')->modify('-1 hours'))->format('%a'));
        $this->view->setVar('show_p_days', (new \DateTime())->diff($this->lotteryService->getNextDateDrawByLottery('PowerBall')->modify('-1 hours'))->format('%a'));
        $this->view->setVar('pageController', 'index');
        $this->view->setVar('main_jackpot_home', $mainJackpotHomeDTO);
        $userPreferenceService = $this->userPreferencesService;
        $jackpotFunc = function(&$euroMillionsDrawJackpotArr,&$userPreferenceService) {
            $dtoArr= [];
            foreach ($euroMillionsDrawJackpotArr as $dto) {
                $jackpot = $userPreferenceService->getJackpotInMyCurrencyAndMillions($dto->jackpot);;
                $numbers = preg_replace('/[A-Z,.]/','',ViewHelper::formatJackpotNoCents($jackpot));
                $letters = preg_replace('/[0-9.,]/','',ViewHelper::formatJackpotNoCents($jackpot));
                $params = ViewHelper::setSemanticJackpotValue($numbers, $letters, $jackpot, $this->languageService->getLocale());
                $size = function($params) {
                    if($params['milliards']) return 'B';
                    if($params['trillions']) return 'T';
                    return 'M';
                };
                $dto->jackpot = $params['jackpot_value'].$size($params);
                array_push($dtoArr,$dto);
            }
            return $dtoArr;
        };
        $this->view->setVar('slide_jackpot_include', $jackpotFunc($euroMillionsDrawJackpotArr,$userPreferenceService));
        $this->tag->prependTitle($this->languageService->translate('home_name') . ViewHelper::formatMillionsJackpot($jackpot) . ' ' . $this->languageService->translate($textMillions));
        MetaDescriptionTag::setDescription($this->languageService->translate('home_desc'));
    }

    public function notfoundAction()
    {
        $this->response->redirect('/error/page404');
    }

    public function billionsAndTrillions($jackpot, $lottery) {
        $numbers = preg_replace('/[^0-9]/','',ViewHelper::formatJackpotNoCents($jackpot));
        $letters = preg_replace('/[0-9.,]/','',ViewHelper::formatJackpotNoCents($jackpot));
        $this->view->setVar('milliards', false);
        $this->view->setVar('trillions', false);
        if ($numbers > 1000 && $this->languageService->getLocale() != 'es_ES') {
            $numbers = round(($numbers / 1000), 1);
            $this->view->setVar('jackpot_value', $letters . ' ' . $numbers);
            $this->view->setVar('milliards_'.$lottery, true);
            $textMillions = 'billion';
        } elseif ($numbers > 1000000 && $this->languageService->getLocale() != 'es_ES') {
            $numbers = round(($numbers / 1000000), 1);
            $this->view->setVar('jackpot_value', $letters . ' ' . $numbers);
            $this->view->setVar('trillions_'.$lottery, true);
            $textMillions = 'trillion';
        } else{
            $this->view->setVar('milliards_'.$lottery, false);
            $this->view->setVar('trillions_'.$lottery, false);
            $textMillions = 'million';
        }
        return $textMillions;
    }
}

