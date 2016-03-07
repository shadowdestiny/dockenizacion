<?php
namespace EuroMillions\web\services\external_apis\CurrencyConversion;

use EuroMillions\web\interfaces\ICurrencyApiCacheStrategy;
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
     * @param string $from
     * @param string $to
     * @param float $rate
     */
    public function setRate($from, $to, $rate)
    {
        $key = $this->getRateKey($from, $to);
        $this->cache->save($key, $rate);
    }

    /**
     * @param string $from
     * @param string $to
     * @return float
     */
    public function getRate($from, $to)
    {
        $key = $this->getRateKey($from, $to);
        return $this->cache->get($key);

    }

    /**
     * @param string $base
     * @param string $to
     */
    public function setConversionFromBase($base, $to)
    {
        $conversions = $this->getConversionsToFetch();
        if (count($conversions) === 0) {
            $conversions = [];
        }
        if (!array_key_exists($base, $conversions)) {
            $conversions[$base] = [];
        }
        if (!in_array($to, $conversions[$base], true)) {
            $conversions[$base][] = $to;
        }
        $this->cache->save(self::RATES_TO_FETCH_KEY, json_encode($conversions));
    }

    /**
     * @return array
     */
    public function getConversionsToFetch()
    {
        $conversions = $this->cache->get(self::RATES_TO_FETCH_KEY);
        return $conversions ? json_decode($conversions, true) : [];
    }

    public static function getRateKey($from, $to)
    {
        return 'CurrencyApi_rateFrom_' . $from . '_to_' . $to;
    }
}