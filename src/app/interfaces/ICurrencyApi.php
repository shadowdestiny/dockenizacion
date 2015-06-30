<?php
namespace EuroMillions\interfaces;
use Money\Currency;

interface ICurrencyApi
{
    /**
     * @param Currency $currencyFrom
     * @param Currency $currencyTo
     * @return \Money\CurrencyPair
     */
    public function getRate(Currency $currencyFrom, Currency $currencyTo);
}