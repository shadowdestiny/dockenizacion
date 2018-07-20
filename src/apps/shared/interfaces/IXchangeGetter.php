<?php
namespace EuroMillions\shared\interfaces;

interface IXchangeGetter
{
    /**
     * @param string $fromCurrencyName
     * @param string $toCurrencyName
     * @return float
     */
    public function getRate($fromCurrencyName, $toCurrencyName);
}