<?php
namespace EuroMillions\web\services\external_apis\CurrencyConversion;

use EuroMillions\web\interfaces\ICurrencyApiCacheStrategy;
use EuroMillions\web\interfaces\IXchangeGetter;
use Phalcon\Http\Client\Provider\Curl;

class CurrencyLayerApi implements IXchangeGetter
{
    private $apiKey;
    private $curl;
    private $cache;

    public function __construct($apiKey, Curl $curl, ICurrencyApiCacheStrategy $cache)
    {
        $this->apiKey = $apiKey;
        $this->curl = $curl;
        $this->cache = $cache;
    }

    public function getRate($fromCurrencyName, $toCurrencyName)
    {

        $result = $this->cache->getRate($fromCurrencyName, $toCurrencyName);
        if (null === $result) {
            $this->cache->setConversionFromBase($fromCurrencyName, $toCurrencyName);
            $conversions_to_fetch = $this->cache->getConversionsToFetch();
            foreach( $conversions_to_fetch as $base => $conversions) {
                $currencies_to = implode(',', $conversions);
                $url = 'https://apilayer.net/api/live?access_key=' . $this->apiKey . '&source=' . $base . '&currencies=' . $currencies_to;
                $new_conversions = json_decode($this->curl->get($url));
                foreach($new_conversions->quotes as $key => $value) {
                    $to = substr($key, 3, 3);
                    $this->cache->setRate($base, $to, (float)$value);
                    if ($base === $fromCurrencyName && $to === $toCurrencyName) {
                        $result = (float)$value;
                    }
                }

            }
        }
        return $result;
    }
}