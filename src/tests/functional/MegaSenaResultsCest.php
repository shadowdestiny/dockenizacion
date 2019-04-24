<?php

use EuroMillions\tests\helpers\mothers\LotteryMother;
use EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother;

class MegaSenaResultsCest
{

    /**
     * @group megasena-results
     * @param FunctionalTester $I
     */
    public function seeMegaSenaResults(FunctionalTester $I)
    {
        $anEuroMillions = LotteryMother::anEuroMillions();
        $next_draw_date = $anEuroMillions->getNextDrawDate(new \DateTime());

        $I->haveInDatabase(
            'euromillions_draws',
            EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()
                ->withLottery($anEuroMillions)
                ->withId(1)
                ->withDrawDate($next_draw_date)
                ->build()->toArray()
        );

        $I->haveInDatabase(
            'euromillions_draws',
            EuroMillionsDrawMother::aMegaSenaDrawWithJackpotAndBreakDown()
                ->withLottery(LotteryMother::aMegaSena())
                ->withId(2)
                ->withDrawDate(new \DateTime('2019-01-02'))
                ->build()->toArray()
        );

        $I->amOnPage('/megasena/results/draw-history/2019-01-02');
        $I->canSee('1  41  44  46  54  58');
    }
}
