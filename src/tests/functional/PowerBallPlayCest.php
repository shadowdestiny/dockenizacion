<?php

use Codeception\Util\Locator;


/**
 * Class PowerBallPlayCest
 */
class PowerBallPlayCest
{
    public function _before(FunctionalTester $I)
    {

    }

    public function _after(FunctionalTester $I)
    {

    }

    /**
     * @group powerball
     * @param FunctionalTester $I
     */
    public function itShouldCreateNewDraw(FunctionalTester $I)
    {
        $I->expect('Can see new draw and new jackpot value');
        $I->amOnPage('/powerball/play');
        $I->canSeeNumRecords(2, 'euromillions_draws');
    }

    /**
     * @group powerball
     * @param FunctionalTester $I
     */
    public function itShouldShowCutOffCorrectly(FunctionalTester $I)
    {
        $I->expect('To see cut off modal');
        $this->poopulateDatabase($I);
        $I->amOnPage('/powerball/play');
        $I->seeElement(Locator::find('div', ['id' => 'closeticket']));
    }

    private function poopulateDatabase(FunctionalTester $I)
    {
        //TODO Dynamic draw time and frequency depends on day time test run
        $I->updateInDatabase('lotteries', array('draw_time' => '12:00:00','frequency' => 'w0100001'), array('id' => '3'));
    }

}
