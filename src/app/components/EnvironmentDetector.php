<?php
namespace app\components;


use app\exceptions\EnvironmentNotSetException;

class EnvironmentDetector
{
    protected $var_name;
    public function __construct($varName)
    {
        $this->var_name = $varName;
    }

    public function getEnvironment()
    {
        $result = getenv($this->var_name);
        if ($result) {
            return $result;
        } else {
            $message = ($result === false) ?  'Environment variable not set' : 'Environment variable is empty';
            throw new EnvironmentNotSetException($message);
        }
    }
}