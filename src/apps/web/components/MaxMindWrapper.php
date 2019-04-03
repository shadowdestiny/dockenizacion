<?php

namespace EuroMillions\web\components;

use GeoIp2\Database\Reader;
use GeoIp2\WebService\Client;
use EuroMillions\web\interfaces\IGeoIPServiceAPI;

class MaxMindWrapper implements IGeoIPServiceAPI
{
    protected $reader;

    private static $forbbidenCountries = ['DE', 'FR'];

    public function __construct($geoipConfig)
    {
        $this->reader = $geoipConfig->use_database ?
            new Reader($geoipConfig->database_path.'/'.$geoipConfig->database_name) :
            new Client($geoipConfig->account_id, $geoipConfig->license_key);
    }

    public function getCountryFromIp($ip)
    {
        try {
            $record = $this->reader->country($ip);
            return $record->country->isoCode;
        } catch (\GeoIp2\Exception\AddressNotFoundException $e) {

        }
    }

    public function isIpForbidden($ip)
    {
        if(in_array($this->getCountryFromIp($ip), self::$forbbidenCountries)) {
            return true;
        }
        return false;
    }
}