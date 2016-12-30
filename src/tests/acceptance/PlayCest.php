<?php

/**
 * Class PlayCest
 */
class PlayCest
{

    protected $cookie;

    public function _before(AcceptanceTester $I)
    {

    }

    /**
     * @group active
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


    protected function login(AcceptanceTester $I)
    {
        $I->amOnPage('/sign-in');
        $I->submitForm('#sign-in-form',['email' => 'nonexisting@email.com','password' => 'Password01']);
    }

    /**
     * @group active
     * @param AcceptanceTester $I
     * @before login
     */
    public function testReact(AcceptanceTester $I)
    {
        $I->amOnPage('/euromillions/play');
        $I->seeCookie('EM_current_user');
        $I->click('.multiplay');
        $I->click('.add-cart');
        $I->canSee('LOG IN');
    }

    /**
     * @group active
     * @param AcceptanceTester $I
     */
    public function testOrder(AcceptanceTester $I)
    {
        $I->seeCookie('EM_current_user');
        $I->amOnPage('/euromillions/order');
        $I->canSee('Review');
    }
}