<?php
namespace EuroMillions\services\external_apis;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
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

    /**
     * @param Currency $currencyFrom
     * @param Currency $currencyTo
     * @return CurrencyPair
     */
    public function getRate(Currency $currencyFrom, Currency $currencyTo)
    {
        if ($currencyFrom->equals($currencyTo)) {
            throw new \InvalidArgumentException('Same currency unnecessary conversion');
        }
        $currency_pair = $this->cache->getConversionRateFor($currencyFrom, $currencyTo);
        if (!$currency_pair) {
            $currency_pair = $this->refreshRates($currencyFrom->getName(), $currencyTo->getName());
        }
        return $currency_pair;
    }

    protected function refreshRates($currencyFromCode, $currencyToCode)
    {
        $conversions = $this->cache->getConversionRatesToFetch();
        $found = false;
        $currencies_string = '';
        if ($conversions) {
            foreach ($conversions as $conversion) {
                $from = key($conversion);
                $to = array_pop($conversion);
                if ($from == $currencyFromCode && $to == $currencyToCode) {
                    $found = true;
                }
                $currencies_string .= "%22{$from}{$to}%22%2C";
            }
        }
        if (!$found) {
            $this->cache->addConversionRateToFetch($currencyFromCode, $currencyToCode);
            $currencies_string .= "%22{$currencyFromCode}{$currencyToCode}%22%2C";
        }
        $currencies_string = substr($currencies_string, 0, -3);
        $url = "https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20({$currencies_string})&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=";
        $response = $this->curl->get($url);
        $rates = json_decode($response->body);

        $result = null;
        $results_to_iterate = is_array($rates->query->results->rate) ? $rates->query->results->rate : $rates->query->results;
        foreach ($results_to_iterate as $rate) {
            $from = substr($rate->id, 0, 3);
            $to = substr($rate->id, 3, 3);
            $currency_pair = new CurrencyPair(new Currency($from), new Currency($to), $rate->Rate);
            if ($from == $currencyFromCode && $to == $currencyToCode) {
                $result = $currency_pair;
            }
            $this->cache->setConversionRate($currency_pair);
        }
        return $result;
    }
}