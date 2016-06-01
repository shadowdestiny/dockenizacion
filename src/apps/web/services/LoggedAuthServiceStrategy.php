<?php


namespace EuroMillions\web\services;

use EuroMillions\web\interfaces\IAuthLoggerStrategy;

class LoggedAuthServiceStrategy extends AuthService implements IAuthLoggerStrategy
{


    public function check($credentials, $agentIdentificationString)
    {
        $user = $this->userRepository->getByEmail($credentials['email']);
        $this->logService->logIn($user);
        return parent::check($credentials, $agentIdentificationString);
    }

    public function logout()
    {
        $this->logService->logOut($this->getCurrentUser());
        parent::logout();
    }

    public function register(array $credentials)
    {
            $this->logService->logRegistration($this->getCurrentUser());
            return parent::register($credentials);
    }

    protected function logError($message, $action)
    {
        $this->logService->error($message, $action);
    }

}