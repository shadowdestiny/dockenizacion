<?php


namespace EuroMillions\tests\helpers\mothers;


use EuroMillions\web\entities\Lottery;
use Money\Currency;
use Money\Money;

class LotteryMother
{
    public static function anEuroMillions()
    {
        $lottery = new Lottery();
        $lottery->initialize([
            'id'        => 1,
            'name'      => 'EuroMillions',
            'frequency' => 'w0100100',
            'draw_time' => '20:00:00',
            'active' => 0,
            'single_bet_price' => new Money((int) 300, new Currency('EUR'))
        ]);
        return $lottery;
    }

    public static function aPowerBall()
    {
        $lottery = new Lottery();
        $lottery->initialize([
            'id'        => 3,
            'name'      => 'PowerBall',
            'frequency' => 'w0001001',
            'draw_time' => '03:30:00',
            'active' => 0,
            'single_bet_price' => new Money((int) 350, new Currency('EUR'))
        ]);
        return $lottery;
    }

    public static function aMegaMillions()
    {
        $lottery = new Lottery();
        $lottery->initialize([
            'id'        => 4,
            'name'      => 'MegaMillions',
            'frequency' => 'w0001001',
            'draw_time' => '03:30:00',
            'active'    => 0,
            'single_bet_price' => new Money((int) 350, new Currency('EUR')),
        ]);
        return $lottery;
    }
    public static function aEuroJackpot()
    {
        $lottery = new Lottery();
        $lottery->initialize([
            'id'        => 5,
            'name'      => 'EuroJackpot',
            'frequency' => 'w0001011',
            'draw_time' => '03:30:00',
            'active'    => 1,
            'single_bet_price' => new Money((int) 350, new Currency('EUR')),
        ]);
        return $lottery;
    }


}