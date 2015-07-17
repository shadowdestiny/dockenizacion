<?php
namespace EuroMillions\interfaces;
use Money\Currency;
interface ICurrencyStrategy
{
    /**
     * @return Currency
     */
    public function get();
    public function set(Currency $currency);
}