<?php
namespace EuroMillions\web\interfaces;

interface ICurrencyApiCacheStrategy
{
    /**
     * @param string $from
     * @param string $to
     * @return float
     */
    public function getRate($from, $to);

    /**
     * @param string $base
     * @param string $to
     * @return void
     */
    public function setConversionFromBase($base, $to);

    /**
     * @return array
     */
    public function getConversionsToFetch();

    /**
     * @param string $from
     * @param string $to
     * @param float $rate
     * @return void
     */
    public function setRate($from, $to, $rate);
}