<?php

namespace EuroMillions\web\controllers;

use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\components\ViewHelper;

class PowerballHelpController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $config = $this->di->get('config');
        /** @var ActionResult $result */
        $lottery = $this->lotteryService->getLotteryConfigByName('EuroMillions');
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot('EuroMillions'));
        $this->view->setVar('jackpot_value', ViewHelper::formatJackpotNoCents($jackpot));
        $numbers = preg_replace('/[A-Z,.]/','',ViewHelper::formatJackpotNoCents($jackpot));
        $letters = preg_replace('/[0-9.,]/','',ViewHelper::formatJackpotNoCents($jackpot));
        $this->view->setVar('milliards', false);
        $this->view->setVar('trillions', false);
        if ($numbers > 1000 && $this->languageService->getLocale() != 'es_ES') {
            $numbers = round(($numbers / 1000), 1);
            $this->view->setVar('jackpot_value', $letters . ' ' . $numbers);
            $this->view->setVar('milliards', true);
        } elseif ($numbers > 1000000 && $this->languageService->getLocale() != 'es_ES') {
            $numbers = round(($numbers / 1000000), 1);
            $this->view->setVar('jackpot_value', $letters . ' ' . $numbers);
            $this->view->setVar('trillions', true);
        } else{
            $this->view->setVar('milliards', false);
            $this->view->setVar('trillions', false);
        }
        $this->view->setVar('language', $this->languageService->getLocale());
        $this->tag->prependTitle($this->languageService->translate('howto_pow_name'));
        MetaDescriptionTag::setDescription($this->languageService->translate('howto_pow_desc'));
        $this->view->setVar('pageController', 'powerHowto');
        $this->view->pick('powerball/howtoplay/index');
        return $this->view->setVars([
            'price_bet' => (!empty($lottery)) ? $lottery->getSingleBetPrice()->getAmount() / 10000 : "",
            'draw_time' => (!empty($lottery)) ? $lottery->getDrawTime() : '',
            'email_support' => $config->email_support['email'],
            'show_s_days' => (new \DateTime())->diff($this->lotteryService->getNextDateDrawByLottery('EuroMillions')->modify('-1 hours'))->format('%a'),
            'pageController' => 'powerHowto',
            'date_draw' => $this->lotteryService->getNextDateDrawByLottery('EuroMillions')->modify('-1 hours')->format('Y-m-d H:i:s'),
        ]);
    }
}
