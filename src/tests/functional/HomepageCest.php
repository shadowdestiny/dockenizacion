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

    /**
     * @group buy
     * @param FunctionalTester $I
     */
    public function seePage(FunctionalTester $I)
    {
        $I->wantTo('Ensure that frontpage works even if crons did not');
        $I->canSee('banner1_btn', 'span');
    }

    /**
     * @group buy
     * @param FunctionalTester $I
     */
    public function playButton(FunctionalTester $I)
    {
        $I->wantTo('Be able to play');
        $I->canSeeLink('banner1_btn', '/euromillions/play');
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

    /**
     * @group buy
     * @param FunctionalTester $I
     */
    public function goToPlayPage(FunctionalTester $I)
    {
        $I->wantTo('Go to the play page');
        $I->click('banner1_btn','.btn-theme--big .resizeme');
        $I->canSee('pick 5 numbers and 2 stars');
    }


    private function setEuroMillionsDraw(FunctionalTester $I)
    {
        $I->haveInDatabase(
            'euromillions_draws',
                    \EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()->build()->toArray()
        );
    }
}
