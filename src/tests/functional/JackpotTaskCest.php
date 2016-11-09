<?php
use EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother;
use EuroMillions\tests\helpers\mothers\UserMother;

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
    public function jackpotIsUpdatedTest(FunctionalTester $I, $scenario)
    {
        $I->wantTo('Update the Jackpot for next draw');
        $I->runShellCommand('php '.__DIR__.'/../../apps/cli-test.php jackpot update');
        $I->expect('There\'s one record in the draws table');
        $I->canSeeNumRecords(1, 'euromillions_draws');
        $I->expect('The Jackpot amount is greater than the minimum amount for the lottery');
        $jackpot_amount = $I->grabFromDatabase('euromillions_draws', 'jackpot_amount');
        $I->assertGreaterThan(15000000, $jackpot_amount);
    }

    /**
     * @param FunctionalTester $I
     * @group problems
     */
    public function jackpotReminderWhenThersholdReach( FunctionalTester $I)
    {
        /** @var \EuroMillions\web\entities\User $user */
        $user = UserMother::aUserWith50Eur()->build();
        $userNotifications = [
            'id' => 1,
            'user_id' => '9098299B-14AC-4124-8DB0-19571EDABE55',
            'notification_id' => 1,
            'active' => 1,
            'type_config_value' => 1200000
        ];
        $I->haveInDatabase('users',$user->toArray());
        $I->haveInDatabase('user_notifications', $userNotifications);
        $draw = EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()->withJackpot(new \Money\Money((int) 15000000, new \Money\Currency('EUR')))->build();
        $draw_array = $draw->toArray();
        $I->haveInDatabase('euromillions_draws', $draw_array);
        $I->wantTo('Send email reminder whthreshold reach');
        $I->runShellCommand('php '.__DIR__.'/../../apps/cli-test.php jackpot reminderJackpot');
    }
}
