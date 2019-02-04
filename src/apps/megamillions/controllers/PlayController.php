<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 9/10/18
 * Time: 12:51
 */

namespace EuroMillions\megamillions\controllers;


use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\components\TrackingCodesHelper;
use EuroMillions\web\components\ViewHelper;
use function GuzzleHttp\Psr7\str;
use Money\Currency;
use EuroMillions\web\components\tags\MetaDescriptionTag;

final class PlayController extends \EuroMillions\shared\controllers\PlayController
{

    public function indexAction()
    {
        parent::indexAction();
        $current_currency = $this->userPreferencesService->getCurrency();
        $this->jackpot = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot('MegaMillions'));
        $this->view->setVar('jackpot_value_mega', ViewHelper::formatJackpotNoCents($this->jackpot));
        $numbers = preg_replace('/[A-Z,.]/','',ViewHelper::formatJackpotNoCents($this->jackpot));
        $letters = preg_replace('/[0-9.,]/','',ViewHelper::formatJackpotNoCents($this->jackpot));
        $jackpotSymbol = ViewHelper::setSemanticJackpotValue($numbers,$letters,$this->jackpot,$this->languageService->getLocale());
        $this->view->setVar('jackpot_value_mega', $jackpotSymbol['jackpot_value']);
        $this->view->setVar('milliards', $jackpotSymbol['milliards']);
        $this->view->setVar('trillions', $jackpotSymbol['trillions']);

        $this->singleBetPrice = $this->lotteryService->getSingleBetPriceByLottery('MegaMillions');
        if (!$this->authService->isLogged()) {
            $user_currency = $this->userPreferencesService->getCurrency();
            $this->singleBetPriceCurrency = $this->currencyConversionService->convert($this->singleBetPrice, $user_currency);
        } else {
            $current_user_id = $this->authService->getCurrentUser()->getId();
            /** @var User $user */
            $user = $this->userService->getUser($current_user_id);
            $this->singleBetPriceCurrency = $this->currencyConversionService->convert($this->singleBetPrice, new Currency($user->getUserCurrency()->getName()));
        }

        $this->play_dates = $this->lotteryService->getRecurrentDrawDates('MegaMillions');
        $this->draw = $this->lotteryService->getNextDateDrawByLottery('MegaMillions',null,false);
        $dateTimeToClose =  $this->lotteryService->getNextDateDrawByLottery('MegaMillions',null,true);
        $date_time_util = new DateTimeUtil();
        $this->dayOfWeek = $date_time_util->getDayOfWeek($this->draw);
        $this->checkOpenTicket = $date_time_util->checkTimeForClosePlay($dateTimeToClose);
        $this->singleBetPrice = $this->lotteryService->getSingleBetPriceByLottery('MegaMillions');
        $this->automaticRandom = $this->request->get('random');
        $this->bundlePriceDTO = $this->domainServiceFactory->getPlayService()->retrieveEuromillionsBundlePriceDTO('MegaMillions');
        $single_bet_price = $this->lotteryService->getSingleBetPriceByLottery('MegaMillions');
        $single_bet_price_currency = $this->currencyConversionService->convert($single_bet_price, $current_currency);
        $this->betValue = $this->currencyConversionService->toString($single_bet_price_currency, $current_currency);

        $this->tag->prependTitle($this->languageService->translate('play_megam_name'));
        MetaDescriptionTag::setDescription($this->languageService->translate('play_megam_desc'));

        if($this->request->get('register'))
        {
            $this->view->setVar('register', TrackingCodesHelper::trackingAffiliatePlatformCodeWhenUserIsRegistered());
        }
        return $this->view->setVars([
            'play_dates' => $this->play_dates,
            'next_draw' => $this->dayOfWeek,
            'next_draw_format' => $this->draw->format('l j M G:i'),
            'currency_symbol' => $this->currencySymbol,
            'openTicket' => ($this->checkOpenTicket) ? '1' : '0',
            'single_bet_price' => $this->singleBetPriceCurrency->getAmount() / 100,
            'bet_price' => $this->betValue,
            'automatic_random' => isset($automatic_random) ? true : false,
            'discount_lines_title' => 'Choose your bundle',
            'discount_lines' => json_encode($this->bundlePriceDTO),
            'draws_number' => $this->bundlePriceDTO->bundlePlayDTOActive->getDraws(),
            'discount' => $this->bundlePriceDTO->bundlePlayDTOActive->getDiscount(),
            'pageController' => 'megaPlay',
            'next_draw_date_format' => $this->draw->format($this->languageService->translate('dateformat')),
            'draw_day' => $this->languageService->translate($this->draw->format('l')),
            'power_play_price' => $this->domainServiceFactory->getPlayService()->getPowerPlay()
        ]);

    }

}