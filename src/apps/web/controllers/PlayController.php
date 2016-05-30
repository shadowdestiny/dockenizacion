<?php
namespace EuroMillions\web\controllers;

use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\components\ViewHelper;
use EuroMillions\web\entities\User;
use Money\Currency;

class PlayController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrency($this->lotteryService->getNextJackpot('EuroMillions'));
        $play_dates = $this->lotteryService->getRecurrentDrawDates('EuroMillions');
        $draw = $this->lotteryService->getNextDateDrawByLottery('EuroMillions');
        $date_time_util = new DateTimeUtil();
        $dayOfWeek = $date_time_util->getDayOfWeek($draw);
        $single_bet_price = $this->lotteryService->getSingleBetPriceByLottery('EuroMillions');
        $automatic_random = $this->request->get('random');

        if(!$this->authService->isLogged()) {
            $user_currency = $this->userPreferencesService->getCurrency();
            $single_bet_price_currency  = $this->currencyConversionService->convert($single_bet_price, $user_currency);
        } else {
            $current_user_id = $this->authService->getCurrentUser()->getId();
            /** @var User $user */
            $user = $this->userService->getUser($current_user_id);
            $single_bet_price_currency  = $this->currencyConversionService->convert($single_bet_price, new Currency($user->getUserCurrency()->getName()));
        }
        $currency_symbol = $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'];
        $this->tag->prependTitle('Play Euromillions - Jackpot: ' . ViewHelper::formatJackpotNoCents($jackpot) );

        return $this->view->setVars([
            'jackpot_value' => $jackpot,
            'play_dates' => $play_dates,
            'next_draw' => $dayOfWeek,
            'next_draw_format' => $draw->format('l j M G:i'),
            'currency_symbol' => $currency_symbol,
            'single_bet_price' => $single_bet_price_currency->getAmount() /100,
            'automatic_random' => isset($automatic_random) ? true : false,
        ]);
    }
}
