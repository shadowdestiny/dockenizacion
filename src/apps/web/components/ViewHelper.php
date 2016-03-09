<?php
namespace EuroMillions\web\components;

class ViewHelper
{
    const MILD_CURRENCIES = 'CNY,RON,ZAR,SEK';
    const SEVERE_CURRENCIES = 'COP,INR,JPY,RUB,THB';

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
}