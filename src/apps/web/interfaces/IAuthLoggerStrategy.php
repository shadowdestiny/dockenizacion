<?php


namespace EuroMillions\web\interfaces;


interface IAuthLoggerStrategy
{
    public function check($credentials, $agentIdentificationString);
    public function register(array $credentials);
    public function logout();
}