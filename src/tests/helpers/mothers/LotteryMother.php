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
            'single_bet_price' => new Money((int) 300, new Currency('EUR'))
        ]);
        return $lottery;
    }

}