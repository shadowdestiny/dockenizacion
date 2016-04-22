<?php


namespace EuroMillions\tests\helpers\mothers;


use EuroMillions\tests\helpers\builders\PlayConfigBuilder;

class PlayConfigMother
{

    public static function aPlayConfig()
    {
        return PlayConfigBuilder::aPlayConfig();
    }

    /**
     * @param $user
     * @return PlayConfigBuilder
     */
    public static function aPlayConfigSetForUser($user)
    {
        return PlayConfigBuilder::aPlayConfig()
            ->withUser($user);
    }

}