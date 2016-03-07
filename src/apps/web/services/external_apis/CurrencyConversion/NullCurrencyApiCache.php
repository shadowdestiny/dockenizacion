<?php
namespace EuroMillions\web\services\external_apis\CurrencyConversion;

use EuroMillions\web\interfaces\ICurrencyApiCacheStrategy;

class NullCurrencyApiCache implements ICurrencyApiCacheStrategy
{
    private $conversions;
    /**
     * @param string $from
     * @param string $to
     * @return null
     */
    public function getRate($from, $to)
    {
        return null;
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function setRate($from, $to, $rate)
    {
    }
}