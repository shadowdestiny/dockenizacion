<?php

namespace EuroMillions\web\controllers;

use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\entities\User;
use Money\Currency;

class ChristmasController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $single_bet_price = $this->lotteryService->getSingleBetPriceByLottery('Christmas');
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

        $this->tag->prependTitle('Play Christmas Lottery ');
        MetaDescriptionTag::setDescription('Play Christmas Lottery worldwide on the official website of EuroMillions.com and become our next EuroMillionaire!');

        return $this->view->setVars([
            'currencySymbol' => $currency_symbol,
            'singleBetPrice' => $single_bet_price_currency->getAmount() /100,
        ]);
    }
}
