<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 2/08/18
 * Time: 13:26
 */

namespace EuroMillions\shared\services\payments_load_balancer_strategies;


use EuroMillions\web\services\card_payment_providers\pgwlb\ILoadBalancingPayment;
use Exception;
use EuroMillions\web\services\GeoIPService;
use Phalcon\Config;

class GeoIPStrategy implements ILoadBalancingPayment
{

    CONST FIELDS = ['normal','blocked','geoip','geoIpImpl','database_path','blocked_countries'];

    protected $normalGW;
    protected $blockedGW;
    /** @var GeoIPService  */
    protected $geoipImpl;
    protected $blocked_countries;
    private $instance;

    public function __construct(\EuroMillions\shared\components\PaymentsCollection $payments, Config $params)
    {
        if($this->guard($params)){
            throw new \Exception('Miss params in GeoIPStrategy constructor');
        }
        $this->normalGW = $payments->getItem($params->normal);
        $this->blockedGW = $payments->getItem($params->blocked);
        $this->geoipImpl = $this->geoService($params);
        $this->blocked_countries = [$params->blocked_countries];
        $this->makeStrategy();
    }

    public function makeStrategy()
    {
        if(in_array($this->geoipImpl->countryFromIP($this->getIp()),$this->blocked_countries))
        {
            $this->instance = $this->blockedGW;
        } else {
            $this->instance = $this->normalGW;
        }
    }

    public function getInstance()
    {
        return $this->instance;
    }

    private function geoService($params)
    {
        $service = "\\EuroMillions\\web\\services\\" . $params->geoip;
        $geoIpWrapper = "\\EuroMillions\\web\\components\\". $params->geoIpImpl;
        $wrapper = new $geoIpWrapper($params->database_path);
        return new $service($wrapper);
    }

    private function guard(Config $params)
    {
        $config = [];
        foreach ($params as $k => $param) {
            $config[] = $k;
         }
        return count(array_diff(self::FIELDS,$config)) > 0;
    }

    private function getIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}