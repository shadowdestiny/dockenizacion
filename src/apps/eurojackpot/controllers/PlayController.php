<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 9/10/18
 * Time: 12:51
 */

namespace EuroMillions\eurojackpot\controllers;


use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\components\ViewHelper;
use Money\Currency;

final class PlayController extends \EuroMillions\shared\controllers\PlayController
{

    public function indexAction()
    {
        parent::indexAction();
        $current_currency = $this->userPreferencesService->getCurrency();
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot('EuroJackpot'));
        $this->view->setVar('jackpot_value_eurojackpot', ViewHelper::formatJackpotNoCents($jackpot));
        $numbers = preg_replace('/[A-Z,.]/','',ViewHelper::formatJackpotNoCents($jackpot));
        $letters = preg_replace('/[0-9.,]/','',ViewHelper::formatJackpotNoCents($jackpot));
        $jackpotSymbol = ViewHelper::setSemanticJackpotValue($numbers,$letters,$jackpot,$this->languageService->getLocale());
        $this->view->setVar('jackpot_value_eurojackpot', $jackpotSymbol['jackpot_value']);
        $this->view->setVar('milliards', $jackpotSymbol['milliards']);
        $this->view->setVar('trillions', $jackpotSymbol['trillions']);

        $this->singleBetPrice = $this->lotteryService->getSingleBetPriceByLottery('EuroJackpot');
        if (!$this->authService->isLogged()) {
            $user_currency = $this->userPreferencesService->getCurrency();
            $this->singleBetPriceCurrency = $this->currencyConversionService->convert($this->singleBetPrice, $user_currency);
        } else {
            $current_user_id = $this->authService->getCurrentUser()->getId();
            /** @var User $user */
            $user = $this->userService->getUser($current_user_id);
            $this->singleBetPriceCurrency = $this->currencyConversionService->convert($this->singleBetPrice, new Currency($user->getUserCurrency()->getName()));
        }

        $this->play_dates = $this->lotteryService->getRecurrentDrawDates('EuroJackpot');
        $this->draw = $this->lotteryService->getNextDateDrawByLottery('EuroJackpot');
        $date_time_util = new DateTimeUtil();
        $this->dayOfWeek = $date_time_util->getDayOfWeek($this->draw);
        $this->checkOpenTicket = $date_time_util->checkTimeForClosePlay($this->draw);
        $this->singleBetPrice = $this->lotteryService->getSingleBetPriceByLottery('EuroJackpot');
        $this->automaticRandom = $this->request->get('random');
        $this->bundlePriceDTO = $this->domainServiceFactory->getPlayService()->retrieveEuromillionsBundlePriceDTO('EuroJackpot');
        $single_bet_price = $this->lotteryService->getSingleBetPriceByLottery('EuroJackpot');
        $single_bet_price_currency = $this->currencyConversionService->convert($single_bet_price, $current_currency);
        $this->betValue = $this->currencyConversionService->toString($single_bet_price_currency, $current_currency);

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
            'pageController' => 'euroPlay',
            'next_draw_date_format' => $this->draw->format($this->languageService->translate('dateformat')),
            'draw_day' => $this->languageService->translate($this->draw->format('l')),
            'power_play_price' => $this->domainServiceFactory->getPlayService()->getPowerPlay()
        ]);
    }

}