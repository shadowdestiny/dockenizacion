<?php

/**
 * Class BackToResultsCest
 */
class BackToResultsCest
{
    /**
     * @group active
     * @param FunctionalTester $I
     */
    public function iAmOnPage(FunctionalTester $I)
    {
        $I->haveInDatabase('euromillions_draws', [
            'id'         => 1,
            'lottery_id' => 1,
            'draw_date'  => '2016-11-01',
        ]);

        $I->amOnPage('/euromillions/results/draw-history-page/2016-11-01');
    }

    /**
     * @group active
     * @param FunctionalTester $I
     */
    public function seeButtonBackResults(FunctionalTester $I)
    {
        $I->wantTo('I can see button back results');
        $I->canSee('Back to Results List');
    }

    /**
     * @group active
     * @param FunctionalTester $I
     */
    public function linkOfResultsButton(FunctionalTester $I)
    {
        $I->wantTo('Check value link of results button');
        $I->seeLink('Back to Results List', '/euromillions/results/');
    }
}
