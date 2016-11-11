<?php
namespace EuroMillions\web\components;

use Phalcon\Tag;

class ViewHelper
{
    const MILD_CURRENCIES = 'CNY,RON,ZAR,SEK,ARS,BOB,BRL,GEL,HKD,ILS,MXN,MYR,NOK,PEN,PLN,QAR,TRY,UAH,VEF';
    const SEVERE_CURRENCIES = 'BYR,COP,INR,JPY,RUB,THB,ALL,CLP,CZK,HUF,IDR,ISK,KES,KRW,KZT,LBP,MDL,MKD,NGN,PHP,PKR,PYG,RSD';
    const TIME_LEFT_COUNTDOWN = 30; //seconds
    const TIME_LIMIT_SHOW_UNTIL_BET = 10 * 60; //minutes to seconds
    const HOURS_SHOW_COUNT_DOWN = 5;

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


    public static function getNamePaymentType( $nameClass )
    {
        return $nameClass instanceof Tag ? 'iframe' : 'form';
    }

    public static function setCountDownFinishBet(\DateTime $nextDrawDate, \DateTime $datetime=null)
    {
        if($datetime == null) {
            $datetime = new \DateTime();
        }
        $nextDrawDate->setTime('18', '50');


        $datetime->modify('+1 day');

        if ($datetime->diff($nextDrawDate)->d == 0 && $datetime->diff($nextDrawDate)->h < self::HOURS_SHOW_COUNT_DOWN && $datetime->diff($nextDrawDate)->i <= 59 && $datetime->diff($nextDrawDate)->invert == 0) {
            return [
                'hours' => $datetime->diff($nextDrawDate)->h,
                'minutes' => $datetime->diff($nextDrawDate)->i,
                'seconds' => $datetime->diff($nextDrawDate)->s,
                'timeLeftCountDown' => self::TIME_LEFT_COUNTDOWN,
                'diffTimeActualTimeAndNextDrawTime' => $nextDrawDate->getTimestamp() - $datetime->getTimestamp(),
                'timeAppearCountDownAgain' => $nextDrawDate->getTimestamp() - $datetime->getTimestamp() - self::TIME_LIMIT_SHOW_UNTIL_BET,
                'timeLimitAppearCountDown' => self::TIME_LIMIT_SHOW_UNTIL_BET,
            ];
        }

        return [];
    }

}