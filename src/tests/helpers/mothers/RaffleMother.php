<?php


namespace EuroMillions\tests\helpers\mothers;


use EuroMillions\web\vo\Raffle;

class RaffleMother
{
    public static function anRaffle()
    {
        return new Raffle(
            'BNN41949'
        );
    }
}