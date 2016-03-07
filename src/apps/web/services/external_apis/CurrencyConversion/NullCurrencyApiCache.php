<?php
namespace EuroMillions\web\services\external_apis\CurrencyConversion;

use EuroMillions\web\interfaces\ICurrencyApiCacheStrategy;

class NullCurrencyApiCache implements ICurrencyApiCacheStrategy
{
    private $conversions;
    /**
     * @param string $from
     * @param string $to
     * @return float
     */
    public function getRate($from, $to)
    {
        return null;
    }

    /**
     * @param string $base
     * @param string $to
     */
    public function setConversionFromBase($base, $to)
    {
        $this->conversions = [$base => [$to]];
    }

    /**
     * @return array
     */
    public function getConversionsToFetch()
    {
        return $this->conversions;
    }

    /**
     * @param string $from
     * @param string $to
     * @param float $rate
     */
    public function setRate($from, $to, $rate)
    {
    }
}