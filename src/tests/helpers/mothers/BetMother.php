<?php


namespace EuroMillions\tests\helpers\mothers;


use EuroMillions\tests\helpers\builders\BetBuilder;

class BetMother
{


    public static function aSingleBet()
    {
        $playConfig = PlayConfigMother::aPlayConfigSetForUser(UserMother::aUserWith50Eur()->build())->build();
        $draw= EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()->build();

        $bet= new BetBuilder($playConfig, $draw);
        return $bet->build();
    }

}