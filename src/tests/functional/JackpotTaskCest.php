<?php

/**
 * Class JackpotTaskCest
 */
class JackpotTaskCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function jackpotIsUpdatedTest(FunctionalTester $I)
    {
        $I->wantTo('Update the Jackpot for next draw');
        $I->runShellCommand('php '.__DIR__.'/../../apps/cli-test.php jackpot update');
        $I->expect('There\'s one record in the draws table');
        $I->canSeeNumRecords(1, 'euromillions_draws');
        $I->expect('The Jackpot amount is greater than the minimum amount for the lottery');
        $jackpot_amount = $I->grabFromDatabase('euromillions_draws', 'jackpot_amount');
        $I->assertGreaterThan(1500000000, $jackpot_amount);
    }
}
