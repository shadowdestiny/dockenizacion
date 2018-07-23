<?php


/**
 * Class PowerBallPlayCest
 */
class PowerBallPlayCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage('/powerball/play');
    }

    public function _after(FunctionalTester $I)
    {

    }

    /**
     * @group buy
     * @param FunctionalTester $I
     */
    public function jackpotDisplayed(FunctionalTester $I)
    {
        $I->wantTo('Be informed of the jackpot');
        $jackpot = $I->grabTextFrom('.desktop-row--01');
        $jackpot_number = (int)str_replace(['.',',','€'],'', $jackpot);
        $I->expect('The Jackpot would be greather or equal than 15M euros');
        $I->assertGreaterThanOrEqual(15, $jackpot_number);
    }

}
