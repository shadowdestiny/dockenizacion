<?php


namespace EuroMillions\web\services;


use EuroMillions\web\interfaces\IAuthLoggerStrategy;

class LoggedAuthServiceNullStrategy extends AuthService implements IAuthLoggerStrategy
{

    public function check($credentials, $agentIdentificationString)
    {
        return parent::check($credentials, $agentIdentificationString);
    }

    public function logout()
    {
        parent::logout();
    }

    public function register(array $credentials)
    {
        return parent::register($credentials);
    }


}