<?php
namespace EuroMillions\vo\base;

use EuroMillions\vo\Hostname;
use EuroMillions\vo\IPAddress;

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