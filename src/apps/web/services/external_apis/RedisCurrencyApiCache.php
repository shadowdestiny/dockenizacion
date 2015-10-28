<?php
namespace EuroMillions\web\services\external_apis;

use EuroMillions\web\interfaces\ICurrencyApiCacheStrategy;
use Money\Currency;
use Money\CurrencyPair;
use Phalcon\Cache\Backend\Redis;

class RedisCurrencyApiCache implements ICurrencyApiCacheStrategy
{
    const RATES_TO_FETCH_KEY = 'CurrencyApi_ratesToFetch';
    /** @var Redis */
    protected $cache;

    public function __construct(Redis $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param array $currencies
     */
    public function setConversionRatesToFetch(array $currencies)
    {
        $this->cache->save(self::RATES_TO_FETCH_KEY, $currencies);
    }

    /**
     * @return array
     */
    public function getConversionRatesToFetch()
    {
        return $this->cache->get(self::RATES_TO_FETCH_KEY);
    }

    /**
     * @param Currency $fromCurrency
     * @param Currency $toCurrency
     * @return CurrencyPair
     */
    public function getConversionRateFor(Currency $fromCurrency, Currency $toCurrency)
    {
        $key = $this->getRateKey($fromCurrency, $toCurrency);
        return $this->cache->get($key);
    }

    public function setConversionRate(CurrencyPair $currencyPair)
    {
        $key = $this->getRateKey($currencyPair->getBaseCurrency(), $currencyPair->getCounterCurrency());
        $this->cache->save($key, $currencyPair);
    }

    /**
     * @param string $from
     * @param string $to
     */
    public function addConversionRateToFetch($from, $to)
    {
        $rates_to_fetch = $this->cache->get(self::RATES_TO_FETCH_KEY);
        if (!$rates_to_fetch) {
            $rates_to_fetch = [];
        }
        array_push($rates_to_fetch, [$from => $to]);
        $this->cache->save(self::RATES_TO_FETCH_KEY, $rates_to_fetch);
    }

    public static function getRateKey(Currency $from, Currency $to)
    {
        return 'CurrencyApi_rateFrom_'.$from->getName().'_to_'.$to->getName();
    }
}