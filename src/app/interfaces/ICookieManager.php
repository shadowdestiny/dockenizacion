<?php
namespace EuroMillions\interfaces;

interface ICookieManager 
{
    public function get($cookieName);
    public function set($cookieName, $value, $expiration);
    public function delete($cookieName);
    public function has($cookieName);
}