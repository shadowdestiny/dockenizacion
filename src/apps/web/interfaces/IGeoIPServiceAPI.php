<?php


namespace EuroMillions\web\interfaces;


interface IGeoIPServiceAPI
{
    public function getCountryFromIp($ip);
}