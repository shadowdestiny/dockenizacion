<?php


namespace EuroMillions;


use EuroMillions\interfaces\ICypherStrategy;

class NullCypher implements ICypherStrategy
{

    public function encrypt($key, $clear)
    {
        return $clear;
    }

    public function decrypt($cyphered, $key)
    {
        return $cyphered;
    }

    public function getSignature($content_cypehered)
    {
        return $content_cypehered;
    }
}