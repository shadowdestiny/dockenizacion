<?php
namespace EuroMillions\web\components;

class ViewHelper
{
    const MILD_CURRENCIES = 'CNY,RON,ZAR,SEK,ARS,BOB,BRL,GEL,HKD,ILS,MXN,MYR,NOK,PEN,PLN,QAR,TRY,UAH,VEF';
    const SEVERE_CURRENCIES = 'BYR,COP,INR,JPY,RUB,THB,ALL,CLP,CZK,HUF,IDR,ISK,KES,KRW,KZT,LBP,MDL,MKD,NGN,PHP,PKR,PYG,RSD';

    public static function getBodyCssForCurrency($currencyCode)
    {
        $mild = explode(',', self::MILD_CURRENCIES);
        $severe = explode(',', self::SEVERE_CURRENCIES);
        if (in_array($currencyCode, $mild, true)) {
            return 'cur1 ';
        } elseif (in_array($currencyCode, $severe, true)) {
            return 'cur2 ';
        } else {
            return '';
        }
    }

    public static function formatJackpotNoCents($amount)
    {
        return substr($amount, 0, strpos($amount, "."));
    }



}