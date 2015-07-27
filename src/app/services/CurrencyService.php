<?php
namespace EuroMillions\services;

use antonienko\MoneyFormatter\MoneyFormatter;
use EuroMillions\interfaces\ICurrencyApi;
use Money\Currency;
use Money\Money;

class CurrencyService
{
    private $currencyApi;
    private $languageService;

    public function __construct(ICurrencyApi $currencyApi, LanguageService $languageService)
    {
        $this->currencyApi = $currencyApi;
        $this->languageService = $languageService;
    }

    public function convert(Money $from,Currency $to)
    {
        $currency_pair = $this->currencyApi->getRate($from->getCurrency(), $to);
        return $currency_pair->convert($from);
    }

    public function toString(Money $money)
    {
        $money_formatter = new MoneyFormatter();
        return $money_formatter->toStringByLocale($this->languageService->getLocale(), $money);
    }

    public function getSymbol(Money $money)
    {
        $money_formatter = new MoneyFormatter();
        return $money_formatter->getSymbol($this->languageService->getLocale(), $money);
    }

    public function getActiveCurrenciesCodeAndNames()
    {
        //EMTD
        return [
            ['symbol' => '€', 'code' => 'EUR', 'name' => 'Euro'],
            ['symbol' => '$', 'code' => 'USD', 'name' => 'US Dollar'],
            ['symbol' => 'COP', 'code' => 'COP', 'name' => 'Colombian Peso'],
        ];
    }

}