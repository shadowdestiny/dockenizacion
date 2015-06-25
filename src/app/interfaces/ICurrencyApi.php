<?php
namespace EuroMillions\interfaces;
interface ICurrencyApi
{
    /**
     * @param string $currencyFromCode
     * @param string $currencyToCode
     * @return \Money\CurrencyPair
     */
    public function getRate($currencyFromCode, $currencyToCode);
}