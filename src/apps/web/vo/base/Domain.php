<?php
namespace EuroMillions\web\vo\base;

use EuroMillions\web\vo\Hostname;
use EuroMillions\web\vo\IPAddress;

abstract class Domain extends StringLiteral
{
    public static function specifyType($domain)
    {
        if (false !== filter_var($domain, FILTER_VALIDATE_IP)) {
            return new IPAddress($domain);
        }
        return new Hostname($domain);
    }
}