<?php


namespace EuroMillions\web\services;


use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\IAccountLoggerStrategy;
use EuroMillions\web\vo\Email;
use Money\Currency;

class LoggedUserServiceStrategy extends UserService implements IAccountLoggerStrategy
{

    public function updateUserData(array $user_data, Email $email)
    {
        $user = $this->userRepository->getByEmail($email->toNative());
        if(null == $user) $this->logService->error('user no exist with email ' . $email->toNative(),'updateUserData');
        $this->logService->logUpdateAccountDetails($user);
        return parent::updateUserData($user_data, $email);
    }

    public function updateCurrency(User $user, Currency $new_currency)
    {
        $this->logService->logChangeCurrency($user,$new_currency->getName());
        return parent::updateCurrency($user, $new_currency);
    }

    public function updateLanguage(User $user, $language)
    {
        return parent::updateLanguage($user, $language);
    }

}