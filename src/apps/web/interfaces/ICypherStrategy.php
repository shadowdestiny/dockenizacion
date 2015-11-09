<?php


namespace EuroMillions\web\interfaces;


interface ICypherStrategy
{
    public function encrypt($key,$clear);

    public function decrypt($cyphered, $key);

    public function getSignature($content_cypehered);

}