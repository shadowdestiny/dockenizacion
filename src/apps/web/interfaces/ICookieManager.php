<?php
namespace EuroMillions\web\interfaces;

interface ICookieManager 
{
    public function get($cookieName);
    public function set($cookieName, $value, $expiration = null);
    public function delete($cookieName);
    public function has($cookieName);
}