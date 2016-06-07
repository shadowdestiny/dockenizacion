<?php


namespace EuroMillions\web\interfaces;


use EuroMillions\web\entities\User;
use EuroMillions\web\vo\Email;
use Money\Currency;

interface IAccountLoggerStrategy
{
    public function updateUserData(array $user_data, Email $email);
    public function updateCurrency(User $user, Currency $new_currency);
}