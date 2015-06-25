<?php
namespace EuroMillions\services;

use EuroMillions\interfaces\ICurrencyApi;
use EuroMillions\services\external_apis\NullCurrencyApiCache;
use EuroMillions\services\external_apis\YahooCurrencyApi;

class CurrencyService
{
    public function __construct(ICurrencyApi $currencyApi = null)
    {
        $this->currencyApi = $currencyApi ? $currencyApi : new YahooCurrencyApi(new NullCurrencyApiCache());
    }

    public function convert($amount, $from, $to)
    {
        //$this->currencyApi
    }

}