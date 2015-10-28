<?php
namespace EuroMillions\web\services;

use antonienko\MoneyFormatter\MoneyFormatter;
use EuroMillions\web\interfaces\ICurrencyApi;
use Money\Currency;
use Money\Money;

class CurrencyService
{
    private $currencyApi;

    public function __construct(ICurrencyApi $currencyApi)
    {
        $this->currencyApi = $currencyApi;
    }

    public function convert(Money $from,Currency $to)
    {
        if( $from->getCurrency()->equals($to) ) {
            return $from;
        }
        $currency_pair = $this->currencyApi->getRate($from->getCurrency(), $to);
        return $currency_pair->convert($from);
    }

    public function toString(Money $money, $locale)
    {
        $money_formatter = new MoneyFormatter();
        return $money_formatter->toStringByLocale($locale, $money);
    }

    public function getSymbol(Money $money, $locale)
    {
        $money_formatter = new MoneyFormatter();
        return $money_formatter->getSymbol($locale, $money);
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