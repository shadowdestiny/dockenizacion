<?php
namespace EuroMillions\web\interfaces;

use Money\Currency;
use Money\CurrencyPair;

interface ICurrencyApiCacheStrategy
{
    /**
     * @param array $currencies
     */
    public function setConversionRatesToFetch(array $currencies);

    /**
     * @return array
     */
    public function getConversionRatesToFetch();

    /**
     * @param Currency $fromCurrency
     * @param Currency $toCurrency
     * @return CurrencyPair
     */
    public function getConversionRateFor(Currency $fromCurrency, Currency $toCurrency);

    public function setConversionRate(CurrencyPair $currencyPair);

    /**
     * @param string $from
     * @param string $to
     */
    public function addConversionRateToFetch($from, $to);
}