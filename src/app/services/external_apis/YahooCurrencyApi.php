<?php
namespace EuroMillions\services\external_apis;

use EuroMillions\interfaces\ICurrencyApi;
use EuroMillions\interfaces\ICurrencyApiCacheStrategy;
use Money\Currency;
use Money\CurrencyPair;
use Phalcon\Http\Client\Provider\Curl;

class YahooCurrencyApi implements ICurrencyApi
{
    protected $curl;
    protected $cache;

    public function __construct(ICurrencyApiCacheStrategy $cache, Curl $curlWrapper = null)
    {
        $this->curl = $curlWrapper ? $curlWrapper : new Curl();
        $this->cache = $cache;
    }

    public function getRates(array $currencies)
    {
        $currencies_string = '';
        foreach ($currencies as $currency) {
            $currencies_string .= "%22EUR{$currency}%22%2C";
        }
        $currencies_string = substr($currencies_string, 0, -3);
        $url = "https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20({$currencies_string})&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=";
        $response = $this->curl->get($url);
        $results = json_decode($response->body);
        $rates = [];
        foreach ($results->query->results->rate as $rate) {
            $currency = substr($rate->id, 3, 3);
            $rates[] = new CurrencyPair(new Currency('EUR'), new Currency($currency), $rate->Rate);
        }
        return $rates;
    }

    /**
     * @param string $currencyFromCode
     * @param string $currencyToCode
     * @return CurrencyPair
     */
    public function getRate($currencyFromCode, $currencyToCode)
    {
        $currency_from = new Currency($currencyFromCode);
        $currency_to = new Currency($currencyToCode);
        $currency_pair = $this->cache->getConversionRateFor($currency_from, $currency_to);
        if (!$currency_pair) {
            $this->refreshRates($currencyFromCode, $currencyToCode);
            //$currency_pair = $this->cache->getConversionRateFor($currency_from, $currency_to);
        }
        return $currency_pair;
    }

    protected function refreshRates($currencyFromCode, $currencyToCode)
    {
        $conversions = $this->cache->getConversionRatesToFetch();
        $found = false;
        $currencies_string = '';
        foreach ($conversions as $conversion) {
            $from = key($conversion);
            $to = array_pop($conversion);
            if ($from == $currencyFromCode && $to == $currencyToCode) {
                $found = true;
            }
            $currencies_string .= "%22{$from}{$to}%22%2C";
        }
        if (!$found) {
            $currencies_string .= "%22{$currencyFromCode}{$currencyToCode}%22%2C";
        }
        $currencies_string = substr($currencies_string, 0, -3);
        $url = "https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20({$currencies_string})&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=";
        $response = $this->curl->get($url);
        $rates = json_decode($response->body);
        foreach ($rates->query->results->rate as $rate) {
            $this->cache->setConversionRate(new CurrencyPair(new Currency(substr($rate->id,0,3)), new Currency(substr($rate->id,3,3)), $rate->Rate));
        }
    }
}