<?php
namespace EuroMillions\web\vo;

use EuroMillions\web\vo\base\Domain;

class IPAddress extends Domain
{
    public function __construct($value)
    {
        $value = filter_var($value, FILTER_VALIDATE_IP);
        if ($value === false) {
            throw new \InvalidArgumentException('Invalid IP address');
        }
        parent::__construct($value);
    }
}