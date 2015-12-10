<?php
namespace EuroMillions\shared\components;


use EuroMillions\shared\config\exceptions\EnvironmentNotSetException;

class EnvironmentDetector
{
    protected $var_name;

    const DEFAULT_ENV = 'vagrant';
    private $valid_environments;

    public function __construct($varName)
    {
        $this->var_name = $varName;
        $this->valid_environments = [
            'shippable',
            'vagrant',
            'development',
            'staging',
            'production',
        ];
    }

    public function get()
    {
        $result = getenv($this->var_name);
        if ($result) {
            if (!in_array($result, $this->valid_environments)) {
                throw new EnvironmentNotSetException('Invalid environment var set: "'.$result.'"');
            } else {
                return $result;
            }
        } else {
            $message = ($result === false) ? 'Environment variable not set' : 'Environment variable is empty';
            throw new EnvironmentNotSetException($message);
        }
    }

    public function setDefault()
    {
        if ($this->isEnvSet()) {
            throw new EnvironmentNotSetException('Trying to set an environment where one is yet set');
        } else {
            putenv($this->var_name . '=' . self::DEFAULT_ENV);
        }
    }

    public function isEnvSet()
    {
        $env = getenv($this->var_name);
        return !empty($env);
    }
}