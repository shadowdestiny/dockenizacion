<?php

namespace EuroMillions\web\controllers;

use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\components\ViewHelper;

class HelpController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $config = $this->di->get('config');
        /** @var ActionResult $result */
        $lottery = $this->lotteryService->getLotteryConfigByName('EuroMillions');
        $this->tag->prependTitle($this->languageService->translate('howto_em_name'));
        MetaDescriptionTag::setDescription($this->languageService->translate('howto_em_desc'));
        $this->view->setVar('pageController', 'euroHelp');
        return $this->view->setVars([
            'price_bet' => (!empty($lottery)) ? $lottery->getSingleBetPrice()->getAmount() / 10000 : "",
            'jackpot_value' => ViewHelper::formatJackpotNoCents($this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot('EuroMillions'))),
            'draw_time' => (!empty($lottery)) ? $lottery->getDrawTime() : '',
            'email_support' => $config->email_support['email'],
            'show_s_days' => (new \DateTime())->diff($this->lotteryService->getNextDateDrawByLottery('EuroMillions')->modify('-1 hours'))->format('%a'),
            'pageController' => 'euroFaq',
            'date_draw' => $this->lotteryService->getNextDateDrawByLottery('EuroMillions')->modify('-1 hours')->format('Y-m-d H:i:s'),
        ]);
    }
}
