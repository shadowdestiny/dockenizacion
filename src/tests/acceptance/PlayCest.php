<?php

/**
 * Class PlayCest
 */
class PlayCest
{

    /**
     * @group unic
     * @param AcceptanceTester $I
     */
    public function seeOverlayTimeLeftAppearsAndDisappears(AcceptanceTester $I)
    {
        $I->wantTo('I can see overlay play now');
        $I->amOnPage('/euromillions/play?fakedatetime=2016-11-11');
        $I->canSee('TIME LEFT');
        $I->canSee('PLAY NOW');
        $I->wait(1);
        $I->cantSee('TIME LEFT');
        $I->cantSee('PLAY NOW');
        $I->wait(1);
        $I->canSee('TIME LEFT');
        $I->canSee('PLAY NOW');
    }
}