<?php
namespace EuroMillions\shared\services;


use antonienko\MoneyFormatter\MoneyFormatter;
use EuroMillions\web\interfaces\IXchangeGetter;
use Money\Currency;
use Money\CurrencyPair;
use Money\Money;

class CurrencyConversionService
{
    protected $api;

    public function __construct(IXchangeGetter $api)
    {
        $this->api = $api;
    }

    /**
     * @param Money $from
     * @param Currency $toCurrency
     * @return Money
     * @throws \Money\InvalidArgumentException
     */
    public function convert(Money $from, Currency $toCurrency)
    {
        $from_currency = $from->getCurrency();
        $rate = $this->api->getRate($from_currency->getName(), $toCurrency->getName());
        $pair = new CurrencyPair($from_currency, $toCurrency, $rate);
        return $pair->convert($from);
    }

    public function getRatio(Currency $from, Currency $toCurrency)
    {
        $rate = $this->api->getRate($from->getName(), $toCurrency->getName());
        $pair = new CurrencyPair($from, $toCurrency, $rate);
        return $pair->getRatio();
    }

    /**
     * @param Money $money
     * @param string $locale
     * @return string
     */
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

    public function getSymbolPosition($locale, Currency $currency)
    {
        $money_formatter = new MoneyFormatter();
        try {
            return $money_formatter->getSymbolPosition($locale, $currency);
        }catch(\Exception $e) {
            return 1;
        }
    }

}