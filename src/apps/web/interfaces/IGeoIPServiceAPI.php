<?php


namespace EuroMillions\web\interfaces;


interface IGeoIPServiceAPI
{
    public function getCountryFromIp($ip);
    public function getIsoCodeFromIp($ip);
}