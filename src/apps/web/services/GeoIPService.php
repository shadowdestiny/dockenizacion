<?php


namespace EuroMillions\web\services;

use EuroMillions\web\interfaces\IGeoIPServiceAPI;

class GeoIPService
{

    protected $geoIPServiceAPI;

    public function __construct(IGeoIPServiceAPI $geoIPServiceAPI)
    {
        $this->geoIPServiceAPI = $geoIPServiceAPI;
    }

    public function countryFromIP($ip)
    {
        $this->geoIPServiceAPI->getCountryFromIp($ip);
    }

}