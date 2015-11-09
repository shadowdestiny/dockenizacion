<?php
namespace EuroMillions\web\services\external_apis;

use EuroMillions\interfaces\ICurrencyApiCacheStrategy;
use Money\Currency;
use Money\CurrencyPair;

class NullCurrencyApiCache implements ICurrencyApiCacheStrategy
{

    /**
     * @param array $currencies
     */
    public function setConversionRatesToFetch(array $currencies)
    {
    }

    /**
     * @return array
     */
    public function getConversionRatesToFetch()
    {
        return null;
    }

    /**
     * @param Currency $fromCurrency
     * @param Currency $toCurrency
     * @return CurrencyPair
     */
    public function getConversionRateFor(Currency $fromCurrency, Currency $toCurrency)
    {
        return null;
    }

    public function setConversionRate(CurrencyPair $currencyPair)
    {
    }

    /**
     * @param string $from
     * @param string $to
     */
    public function addConversionRateToFetch($from, $to)
    {
    }
}