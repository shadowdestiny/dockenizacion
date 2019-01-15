<?php

use Money\Currency;
use Money\Money;


/**
 * Class HomepageCest
 */
class HomepageCest
{
    public function _before(FunctionalTester $I)
    {
        $I->haveInDatabase(
            'euromillions_draws',
            \EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()
                ->withId(3)
                ->withDrawDate(new \DateTime('2020-01-10'))
                ->withRaffle(new \EuroMillions\web\vo\Raffle('ABC'))
                ->build()->toArray()
        );
        $I->haveInDatabase(
            'euromillions_draws',
            \EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()->withLottery(
                \EuroMillions\tests\helpers\mothers\LotteryMother::aPowerBall()
            )
                ->withId(4)
                ->withDrawDate(new \DateTime('2020-01-12'))
                ->withRaffle(new \EuroMillions\web\vo\Raffle('ABC'))
                ->withJackpot(new Money(4000000000, new Currency('EUR')))
                ->build()->toArray()
        );
        $I->haveInDatabase(
            'euromillions_draws',
            \EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()->withLottery(
                \EuroMillions\tests\helpers\mothers\LotteryMother::aMegaMillions()
            )
                ->withId(5)
                ->withRaffle(new \EuroMillions\web\vo\Raffle('ABC'))
                ->withDrawDate(new \DateTime('2020-01-11'))
                ->withJackpot(new Money(5000000000, new Currency('EUR')))
                ->build()->toArray()
        );
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


    /**
     * @group home
     * @param FunctionalTester $I
     */
    public function megaMillionsAsMainJackpot(FunctionalTester $I)
    {
        $I->wantTo('MegaMillions jackpot as main jackpot');
        $jackpot = $I->grabTextFrom('.desktop-row--01');
        $jackpot_number = (int)str_replace(['.',',','€'],'', $jackpot);
        $I->assertEquals(50, $jackpot_number);
    }

    /**
     * @group home
     * @param FunctionalTester $I
     */
    public function jackpotOrderedByHeldDate(FunctionalTester $I)
    {
        $I->wantTo('It should show euromillions as first position');
        $lottery = $I->grabTextFrom('.title .resizeme');
        $I->assertEquals('carousel_em_name',$lottery);
    }


    /**
     * @group home
     * @param FunctionalTester $I
     */
    public function euromillionsNextDrawResultsShouldNotBeShowedEmpty(FunctionalTester $I)
    {
        $I->wantTo('Euromillions new draw it should not be showed in carrousel results');
        $result = $I->grabTextFrom('.lottery-result--euromillions .row--results .ball');
        var_dump($result);
    }



}
