<?php


namespace EuroMillions\tests\helpers\mothers;


use EuroMillions\web\vo\Raffle;

class RaffleMother
{
    public static function anRaffle()
    {
        $raffle = new Raffle('BNN41949');

        return $raffle;
    }
}