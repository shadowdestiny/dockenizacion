<?php


namespace tests\acceptance;


class PlayCest
{
    public function _before(\AcceptanceTester $I)
    {
        $I->waitForJS('return document.readyState == "complete"', 10);
        $I->amOnPage('/euromillions/play');
        $I->waitForJS('return document.readyState == "complete"', 10);
    }

    /**
     * @group active
     * @param \AcceptanceTester $I
     */
    public function seePage(\AcceptanceTester $I)
    {
        $I->waitForJS('return document.readyState == "complete"', 10);
        $I->wantTo('I can see play page');
        $I->canSee('Choose 5 numbers & 2 stars per line');
    }

    /**
     * @group active
     * @param \AcceptanceTester $I
     */
    public function seeOrderPageBeingAGuest(\AcceptanceTester $I)
    {
        $I->wait(100);
        $I->wantTo('See my order page when i\'m a guest user');
        $I->click(['class' => 'numbers']);
    }
}