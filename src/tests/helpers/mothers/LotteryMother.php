<?php


namespace EuroMillions\tests\helpers\mothers;


use EuroMillions\web\entities\Lottery;

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
        ]);
        return $lottery;
    }

}