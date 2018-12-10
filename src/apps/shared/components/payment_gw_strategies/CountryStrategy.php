<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 1/08/18
 * Time: 12:14
 */

namespace EuroMillions\shared\components\payment_gw_strategies;


use EuroMillions\web\interfaces\IGeoIPServiceAPI;

class CountryStrategy implements IGeoIPServiceAPI
{

    CONST DATABASE_IPV4_FILE = 'GeoIP.dat';
    CONST DATABASE_IPV6_FILE = 'GeoIPV6.dat';

    protected $filesPath;
    protected $countries;
    protected $reader;

    public function __construct(array $countries, $filesPath)
    {
        $this->countries = $countries;
        $this->filesPath = $filesPath;
    }

    public function getCountryFromIp($ip)
    {

    }

    private function swapReaderByIpType($ip)
    {
        if(strpos($ip, ":") !== false) {
            $this->reader = geoip_open($this->filesPath.'/'.self::DATABASE_IPV6_FILE, GEOIP_STANDARD);
        } else {
            $this->reader = geoip_open($this->filesPath.'/'.self::DATABASE_IPV4_FILE, GEOIP_STANDARD);
        }
    }


}