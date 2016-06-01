<?php


namespace EuroMillions\web\services;


use EuroMillions\web\interfaces\IAccountLoggerStrategy;
use EuroMillions\web\vo\Email;

class LoggedUserServiceStrategy extends UserService implements IAccountLoggerStrategy
{

    public function updateUserData(array $user_data, Email $email)
    {
        $user = $this->userRepository->getByEmail($email->toNative());
        $this->logService->logUpdateAccountDetails($user);
        return parent::updateUserData($user_data, $email);
    }

}