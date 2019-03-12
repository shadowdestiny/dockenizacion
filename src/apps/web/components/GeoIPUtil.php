<?php


namespace EuroMillions\web\components;


class GeoIPUtil
{

    public static function giveMeRealIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') > 0) {
                $ip_list = explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);
                return trim($ip_list[0]);
            } else {
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        }
        else
        {
            if(!isset($_SERVER['REMOTE_ADDR']))
            {
                $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
            }
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}