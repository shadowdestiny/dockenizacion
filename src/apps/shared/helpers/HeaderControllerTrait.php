<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 6/06/18
 * Time: 11:42
 */

namespace EuroMillions\shared\helpers;

use EuroMillions\web\vo\dto\CurrencyDTO;
use Money\Currency;

trait HeaderControllerTrait
{

    protected $userBalance;

    protected $userLanguage;

    protected $currentCurrency;

    protected $currencies;

    protected $currencyList;

    protected $isLogged;

    protected $userCurrency;

    protected $userCurrencyCode;

    protected $activeLanguages;

    protected function setValues(\EuroMillions\web\services\factories\DomainServiceFactory $domainServiceFactory)
    {
        $userPreferencesService = $domainServiceFactory->getUserPreferencesService();
        $authService = $domainServiceFactory->getAuthService();
        $userPreferencesService = $domainServiceFactory->getUserPreferencesService();
        $userService = $domainServiceFactory->getUserService();
        $currencyService = $domainServiceFactory->getCurrencyService();
        $currencyConversionService = $domainServiceFactory->getCurrencyConversionService();
        $lotteryService = $domainServiceFactory->getLotteryService();
        $languageService = $domainServiceFactory->getLanguageService();

        $user_currency = $userPreferencesService->getMyCurrencyNameAndSymbol();
        $current_currency = $userPreferencesService->getCurrency();
        $is_logged = $authService->isLogged();

        if ($is_logged) {
            $user = $authService->getCurrentUser();
            $user = $userService->getUser($user->getId());
            $currency = $userPreferencesService->getCurrency();
            if ($user->getUserCurrency()->getName() != $currency->getName()) {
                $userPreferencesService->setCurrency($user->getUserCurrency());
                $user_currency = $userPreferencesService->getMyCurrencyNameAndSymbol();
            }
            $user_balance = $userService->getBalanceWithUserCurrencyConvert($authService->getCurrentUser()->getId(), $userPreferencesService->getCurrency());
            $user_balance_raw = $currencyConversionService->convert($user->getBalance(), $userPreferencesService->getCurrency())->getAmount();

            $this->userLanguage = $user->getDefaultLanguage();
        } else {
            $user_balance = '';
            $user_balance_raw = '';
            $this->userLanguage = explode('_', $languageService->getLocale())[0];
        }
        $this->userCurrency = $user_currency;
        $this->isLogged = $is_logged;
        $this->currentCurrency = $current_currency;
        $this->userCurrencyCode = $this->currentCurrency->getName();
        $single_bet_price = $lotteryService->getSingleBetPriceByLottery('EuroMillions');
        $single_bet_price_currency = $currencyConversionService->convert($single_bet_price, $current_currency);
        $bet_value = $currencyConversionService->toString($single_bet_price_currency, $current_currency);
        $single_bet_price_currency_gbp = $currencyConversionService->convert($single_bet_price, new Currency('GBP'));
        $bet_value_pound = $currencyConversionService->toString($single_bet_price_currency_gbp, new Currency('GBP'));
        $currencies = $currencyService->getCurrenciesMostImportant();
        $currencies_list = $currencyService->getActiveCurrenciesCodeAndNames();
        if ($currencies->success()) {
            $currencies_dto = [];
            foreach ($currencies->getValues() as $currency) {
                $currencies_dto[] = new CurrencyDTO($currency);
            }
            $this->currencies = $currencies_dto;
            //$this->view->setVars(['currencies' => $currencies_dto]);
            $currencies_dto = [];
            foreach ($currencies_list->getValues() as $currency) {
                $currencies_dto[] = new CurrencyDTO($currency);
            }
            $this->currencyList = $currencies_dto;
        }

        $this->activeLanguages = $languageService->activeLanguagesArray();
    }


}