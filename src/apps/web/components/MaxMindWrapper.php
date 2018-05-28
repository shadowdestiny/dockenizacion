<?php

namespace EuroMillions\web\components;

use EuroMillions\web\interfaces\IGeoIPServiceAPI;

class MaxMindWrapper implements IGeoIPServiceAPI
{
    CONST DATABASE_IPV4_FILE = 'GeoIP.dat';
    CONST DATABASE_IPV6_FILE = 'GeoIPV6.dat';

    protected $filesPath;
    protected $reader;

    private static $forbbidenCountries = ['DE', 'FR'];

    public function __construct($filesPath)
    {
        $this->filesPath = $filesPath;
    }

    public function getCountryFromIp($ip)
    {
        $this->swapReaderByIpType($ip);
        return geoip_country_code_by_addr($this->reader,$ip);
    }

    private function swapReaderByIpType($ip)
    {
        if(strpos($ip, ":") !== false) {
            $this->reader = geoip_open($this->filesPath.'/'.self::DATABASE_IPV6_FILE, GEOIP_STANDARD);
        } else {
            $this->reader = geoip_open($this->filesPath.'/'.self::DATABASE_IPV4_FILE, GEOIP_STANDARD);
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