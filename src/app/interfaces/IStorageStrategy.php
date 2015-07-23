<?php
namespace EuroMillions\interfaces;
use Money\Currency;
interface IStorageStrategy
{
    /**
     * @return Currency
     */
    public function getCurrency();
    public function setCurrency(Currency $currency);

    /**
     * @return IUser
     */
    public function getCurrentUser();
    public function setCurrentUser(IUser $user);
}