<?php
namespace EuroMillions\services;

use EuroMillions\interfaces\ICurrencyApi;
use EuroMillions\services\external_apis\NullCurrencyApiCache;
use EuroMillions\services\external_apis\RedisCurrencyApiCache;
use EuroMillions\services\external_apis\YahooCurrencyApi;
use Money\Currency;
use Money\Money;

class CurrencyService
{
    public function __construct(ICurrencyApi $currencyApi)
    {
        $this->currencyApi = $currencyApi;
    }

    public function convert(Money $from,Currency $to)
    {
        $currency_pair = $this->currencyApi->getRate($from->getCurrency(), $to);
        return $currency_pair->convert($from, $to);
    }
}