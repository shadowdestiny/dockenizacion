<?php
namespace EuroMillions\web\controllers;

use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\components\ViewHelper;
use EuroMillions\web\entities\User;
use Money\Currency;

class PlayController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrency($this->lotteryService->getNextJackpot('EuroMillions'));
        $single_bet_price = $this->lotteryService->getSingleBetPriceByLottery('EuroMillions');
        $automatic_random = $this->request->get('random');
        $drawData = $this->lotteryService->obtainDataForDraw('Euromillions');

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
	    MetaDescriptionTag::setDescription('Play the EuroMillions Lottery worldwide on the official website of EuroMillions.com and become our next EuroMillionaire!');

        return $this->view->setVars([
            'jackpot_value' => ViewHelper::formatJackpotNoCents($jackpot),
            'play_dates' => $drawData['playDates'],
            'next_draw' => $drawData['dayOfWeek'],
            'next_draw_format' => $drawData['drawDate'],
            'currency_symbol' => $currency_symbol,
            'openTicket' => 0,
            'single_bet_price' => $single_bet_price_currency->getAmount() /100,
            'automatic_random' => isset($automatic_random) ? true : false,
        ]);
    }
}
