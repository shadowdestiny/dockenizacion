<?php

namespace EuroMillions\web\components;

use Phalcon\Di;
use GeoIp2\WebService\Client;
use EuroMillions\web\interfaces\IGeoIPServiceAPI;

class MaxMindWrapper implements IGeoIPServiceAPI
{
    protected $reader;

    private static $forbbidenCountries = ['DE', 'FR'];

    public function __construct($filesPath = null)
    {
        $accountId = Di::getDefault()->get('config')['geoip_strategy']->account_id;
        $licenseKey = Di::getDefault()->get('config')['geoip_strategy']->license_key;
        $this->reader = new Client($accountId, $licenseKey);
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