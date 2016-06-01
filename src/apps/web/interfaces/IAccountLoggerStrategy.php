<?php


namespace EuroMillions\web\interfaces;


use EuroMillions\web\vo\Email;

interface IAccountLoggerStrategy
{
    public function updateUserData(array $user_data, Email $email);
}