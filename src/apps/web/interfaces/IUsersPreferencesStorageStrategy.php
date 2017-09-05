<?php
namespace EuroMillions\web\interfaces;
use Money\Currency;
interface IUsersPreferencesStorageStrategy
{
    /**
     * @return Currency
     */
    public function getCurrency();
    public function setCurrency(Currency $currency);
    public function getLanguage();
    public function setLanguage($language);
}