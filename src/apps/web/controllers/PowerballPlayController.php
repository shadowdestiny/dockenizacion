<?php

namespace EuroMillions\web\controllers;

use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\components\TrackingCodesHelper;
use EuroMillions\web\components\ViewHelper;
use EuroMillions\web\entities\User;
use EuroMillions\web\vo\cutoff\PowerBallCutOff;
use Money\Currency;

class PowerballPlayController extends PublicSiteControllerBase
{
    public function indexAction()
    {

        $current_currency = $this->userPreferencesService->getCurrency();
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot('PowerBall'));

        $this->view->setVar('jackpot_value', ViewHelper::formatJackpotNoCents($jackpot));
        $this->view->setVar('jackpot_powerball', ViewHelper::formatJackpotNoCents($jackpot));
        $numbers = preg_replace('/[A-Z,.]/','',ViewHelper::formatJackpotNoCents($jackpot));
        $letters = preg_replace('/[0-9.,]/','',ViewHelper::formatJackpotNoCents($jackpot));
        $this->view->setVar('milliards', false);
        $this->view->setVar('trillions', false);
        if ($numbers > 1000 && $this->languageService->getLocale() != 'es_ES') {
            $numbers = round(($numbers / 1000), 1);
            $this->view->setVar('jackpot_value', $letters . ' ' . $numbers);
            $this->view->setVar('milliards', true);
            $textMillions = 'billion';
        } elseif ($numbers > 1000000 && $this->languageService->getLocale() != 'es_ES') {
            $numbers = round(($numbers / 1000000), 1);
            $this->view->setVar('jackpot_value', $letters . ' ' . $numbers);
            $this->view->setVar('trillions', true);
            $textMillions = 'trillion';
        } else{
            $this->view->setVar('milliards', false);
            $this->view->setVar('trillions', false);
            $textMillions = 'million';
        }
        $this->view->setVar('language', $this->languageService->getLocale());
        $play_dates = $this->lotteryService->getRecurrentDrawDates('PowerBall');
        $draw = $this->lotteryService->getNextDateDrawByLottery('PowerBall',null,false);
        $dateTimeToClose =  $this->lotteryService->getNextDateDrawByLottery('PowerBall',null,true);
        $date_time_util = new DateTimeUtil();
        $dayOfWeek = $date_time_util->getDayOfWeek($draw);
        $checkOpenTicket = (new PowerBallCutOff($dateTimeToClose))->isClosed();
        $single_bet_price = $this->lotteryService->getSingleBetPriceByLottery('PowerBall');
        $automatic_random = $this->request->get('random');
        $bundlePriceDTO = $this->domainServiceFactory->getPlayService()->retrievePowerBallBundlePriceDTO('PowerBall');
        if (!$this->authService->isLogged()) {
            $user_currency = $this->userPreferencesService->getCurrency();
            $single_bet_price_currency = $this->currencyConversionService->convert($single_bet_price, $user_currency);
        } else {
            $current_user_id = $this->authService->getCurrentUser()->getId();
            /** @var User $user */
            $user = $this->userService->getUser($current_user_id);
            $single_bet_price_currency = $this->currencyConversionService->convert($single_bet_price, new Currency($user->getUserCurrency()->getName()));
        }
        $currency_symbol = $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'];
        $this->tag->prependTitle($this->languageService->translate('play_pow_name') . ViewHelper::formatMillionsJackpot($jackpot) . ' ' . $this->languageService->translate($textMillions));
        MetaDescriptionTag::setDescription($this->languageService->translate('play_pow_desc'));
        $single_bet_price = $this->lotteryService->getSingleBetPriceByLottery('PowerBall');
        $single_bet_price_currency = $this->currencyConversionService->convert($single_bet_price, $current_currency);
        $bet_value = $this->currencyConversionService->toString($single_bet_price_currency, $current_currency);
        if($this->request->get('register'))
        {
            $this->view->setVar('register', TrackingCodesHelper::trackingAffiliatePlatformCodeWhenUserIsRegistered());
        }
        $this->view->pick('powerball/play/index');

        return $this->view->setVars([
            'play_dates' => $play_dates,
            'next_draw' => $dayOfWeek,
            'next_draw_format' => $draw->format('l j M G:i'),
            'currency_symbol' => $currency_symbol,
            'openTicket' => ($checkOpenTicket) ? '1' : '0',
            'single_bet_price' => $single_bet_price_currency->getAmount() / 100,
            'bet_price' => $bet_value,
            'automatic_random' => isset($automatic_random) ? true : false,
            'discount_lines_title' => 'Choose your bundle',
            'discount_lines' => json_encode($bundlePriceDTO),
            'draws_number' => $bundlePriceDTO->bundlePlayDTOActive->getDraws(),
            'discount' => $bundlePriceDTO->bundlePlayDTOActive->getDiscount(),
            'pageController' => 'powerPlay',
            'next_draw_date_format' => $draw->format($this->languageService->translate('dateformat')),
            'draw_day' => $this->languageService->translate($draw->format('l')),
            'power_play_price' => $this->domainServiceFactory->getPlayService()->getPowerPlay()
        ]);
    }
}
