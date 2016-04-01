<?php


namespace EuroMillions\tests\helpers\mothers;


use EuroMillions\tests\helpers\builders\PlayConfigBuilder;

class PlayConfigMother
{

    public static function aPlayConfig()
    {
        return self::getInitializedPlayConfig();
    }

    private static function getInitializedPlayConfig()
    {
        return PlayConfigBuilder::aPlayConfig();
    }




}