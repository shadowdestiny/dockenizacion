<?php
namespace EuroMillions\interfaces;
use Money\Currency;
interface IUsersPreferencesStorageStrategy
{
    /**
     * @return Currency
     */
    public function getCurrency();
    public function setCurrency(Currency $currency);
}