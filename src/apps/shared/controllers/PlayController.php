<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 9/10/18
 * Time: 12:52
 */

namespace EuroMillions\shared\controllers;

use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\components\ViewHelper;
use EuroMillions\web\controllers\PublicSiteControllerBase;
use EuroMillions\web\entities\User;
use Money\Currency;

class PlayController extends PublicSiteControllerBase
{

    protected $play_dates;
    protected $dayOfWeek;
    protected $draw;
    protected $currencySymbol;
    protected $checkOpenTicket;
    protected $singleBetPrice;
    protected $singleBetPriceCurrency;
    protected $betValue;
    protected $automaticRandom;
    protected $bundlePriceDTO;

    public function indexAction()
    {
        $current_currency = $this->userPreferencesService->getCurrency();
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
        $this->play_dates = $this->lotteryService->getRecurrentDrawDates('Euromillions');
        $this->draw = $this->lotteryService->getNextDateDrawByLottery('Euromillions');
        $date_time_util = new DateTimeUtil();
        $this->dayOfWeek = $date_time_util->getDayOfWeek($this->draw);
        $this->checkOpenTicket = $date_time_util->checkTimeForClosePlay($this->draw);
        $this->singleBetPrice = $this->lotteryService->getSingleBetPriceByLottery('EuroMillions');
        $this->automaticRandom = $this->request->get('random');
        $this->bundlePriceDTO = $this->domainServiceFactory->getPlayService()->retrieveEuromillionsBundlePriceDTO('EuroMillions');
        if (!$this->authService->isLogged()) {
            $user_currency = $this->userPreferencesService->getCurrency();
            $this->singleBetPriceCurrency = $this->currencyConversionService->convert($this->singleBetPrice, $user_currency);
        } else {
            $current_user_id = $this->authService->getCurrentUser()->getId();
            /** @var User $user */
            $user = $this->userService->getUser($current_user_id);
            $this->singleBetPriceCurrency = $this->currencyConversionService->convert($this->singleBetPrice, new Currency($user->getUserCurrency()->getName()));
        }

        $this->currencySymbol = $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'];
        $this->tag->prependTitle($this->languageService->translate('play_em_name') . ViewHelper::formatMillionsJackpot($jackpot) . ' ' . $this->languageService->translate($textMillions));
        MetaDescriptionTag::setDescription($this->languageService->translate('play_em_desc'));
        $single_bet_price = $this->lotteryService->getSingleBetPriceByLottery('EuroMillions');
        $single_bet_price_currency = $this->currencyConversionService->convert($single_bet_price, $current_currency);
        $this->betValue = $this->currencyConversionService->toString($single_bet_price_currency, $current_currency);
    }
}
