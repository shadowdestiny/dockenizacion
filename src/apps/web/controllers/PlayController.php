<?php
namespace EuroMillions\web\controllers;

use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\entities\User;
use EuroMillions\web\vo\UserId;
use Money\Currency;

class PlayController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrency($this->lotteriesDataService->getNextJackpot('EuroMillions'));
        $play_dates = $this->lotteriesDataService->getRecurrentDrawDates('EuroMillions');
        $draw = $this->lotteriesDataService->getNextDateDrawByLottery('EuroMillions');
        $date_time_util = new DateTimeUtil();
        $dayOfWeek = $date_time_util->getDayOfWeek($draw);
        $single_bet_price = $this->lotteriesDataService->getSingleBetPriceByLottery('EuroMillions');
        $automatic_random = $this->request->get('random');

        if(!$this->authService->isLogged()) {
            $user_currency = $this->userPreferencesService->getCurrency();
            $single_bet_price_currency  = $this->currencyConversionService->convert($single_bet_price, $user_currency);
        } else {
            /** @var UserId $currenct_user_id */
            $current_user_id = $this->authService->getCurrentUser()->getId();
            /** @var User $user */
            $user = $this->userService->getUser($current_user_id);
            $single_bet_price_currency  = $this->currencyConversionService->convert($single_bet_price, new Currency($user->getUserCurrency()->getName()));
        }
        $currency_symbol = $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'];

        return $this->view->setVars([
            'jackpot_value' => $jackpot->getAmount()/100,
            'play_dates' => $play_dates,
            'next_draw' => $dayOfWeek,
            'currency_symbol' => $currency_symbol,
            'single_bet_price' => $single_bet_price_currency->getAmount() /100,
            'automatic_random' => isset($automatic_random) ? true : false,
        ]);
    }
}
