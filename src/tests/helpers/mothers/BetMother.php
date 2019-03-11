<?php


namespace EuroMillions\tests\helpers\mothers;


use EuroMillions\tests\helpers\builders\BetBuilder;

class BetMother
{


    public static function aSingleBet($playConfig = null, $draw = null)
    {
        $playConfig = $playConfig ?: PlayConfigMother::aPlayConfigSetForUser(UserMother::aUserWith50Eur()->build())->build();
        $draw= $draw ?: EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()->build();

        $bet= new BetBuilder($playConfig, $draw);
        return $bet->build();
    }

}