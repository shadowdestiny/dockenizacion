<?php


/**
 * Class HomepageCest
 */
class HomepageCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage('/');
    }

    public function _after(FunctionalTester $I)
    {
    }

    public function seePage(FunctionalTester $I)
    {
        $I->wantTo('Ensure that frontpage works even if crons did not');
        $I->canSee('Home');
    }

    public function playButton(FunctionalTester $I)
    {
        $I->wantTo('Be able to play');
        $I->canSeeLink('PLAY NOW', '/play');
    }

    public function jackpotDisplayed(FunctionalTester $I)
    {
        $I->wantTo('Be informed of the jackpot');
        $jackpot = $I->grabTextFrom('.jackpot .mytxt');
        $jackpot_number = (int)str_replace(['.',',','€'],'', $jackpot);
        $I->expect('The Jackpot would be greather or equal than 15M euros');
        $I->assertGreaterThanOrEqual(15000000, $jackpot_number);
    }

    public function goToPlayPage(FunctionalTester $I)
    {
        $I->wantTo('Go to the play page');
        $I->click('PLAY NOW');
        $I->canSee('Choose 5 numbers & 2 stars per line');
    }
}
