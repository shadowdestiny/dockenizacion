<?php
namespace EuroMillions\shared\components;

use EuroMillions\shared\interfaces\ICookieManager;
use Phalcon\Di;
use Phalcon\Http\Response\Cookies;

class PhalconCookiesWrapper extends Cookies implements ICookieManager
{
    private $environmentPrefix;
    public function __construct(EnvironmentDetector $em)
    {
        $this->environmentPrefix = $em->get().'#';
    }

    public function get($cookieName)
    {
        return parent::get($cookieName);
    }
    public function set($cookieName, $value = null, $expiration = 0, $path = "/", $secure = null, $domain = null, $httpOnly = null)
    {
        parent::set($cookieName, $value, $expiration, $path, $secure, $domain, $httpOnly);
    }
    public function delete($cookieName)
    {
        parent::delete($this->environmentPrefix.$cookieName);
    }
    public function has($cookieName)
    {
        parent::has($this->environmentPrefix.$cookieName);
    }
}