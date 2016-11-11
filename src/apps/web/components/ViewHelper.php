<?php
namespace EuroMillions\web\components;

use Phalcon\Tag;

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


    public static function getNamePaymentType( $nameClass )
    {
        return $nameClass instanceof Tag ? 'iframe' : 'form';
    }

    /**
     * @param int $timeLeftCountDown
     * @param int $timeLimitShowUntilBet
     * @param int $hoursShowCountDown
     * @param \DateTime $nextDrawDate
     * @param \DateTime|null $datetime
     * @return array
     */
    public static function setCountDownFinishBet($timeLeftCountDown, $timeLimitShowUntilBet, $hoursShowCountDown, \DateTime $nextDrawDate, \DateTime $datetime=null)
    {
        if($datetime == null) {
            $datetime = new \DateTime();
        }
        $nextDrawDate->setTime('18', '50');

        if ($datetime->diff($nextDrawDate)->d == 0 && $datetime->diff($nextDrawDate)->h < $hoursShowCountDown && $datetime->diff($nextDrawDate)->i <= 59 && $datetime->diff($nextDrawDate)->invert == 0) {
            return [
                'hours' => $datetime->diff($nextDrawDate)->h,
                'minutes' => $datetime->diff($nextDrawDate)->i,
                'seconds' => $datetime->diff($nextDrawDate)->s,
                'timeLeftCountDown' => $timeLeftCountDown,
                'diffTimeActualTimeAndNextDrawTime' => $nextDrawDate->getTimestamp() - $datetime->getTimestamp(),
                'timeAppearCountDownAgain' => $nextDrawDate->getTimestamp() - $datetime->getTimestamp() - $timeLimitShowUntilBet * 60,
                'timeLimitAppearCountDown' => $timeLimitShowUntilBet * 60,
            ];
        }

        return [];
    }

}