<?php


namespace EuroMillions\web\components;


use EuroMillions\web\interfaces\IGeoIPServiceAPI;
use MaxMind\Db\Reader;

class MaxMindWrapper implements IGeoIPServiceAPI
{


    public function getCountryFromIp($ip)
    {
    }

    public function getIsoCodeFromIp($ip)
    {

    }
}