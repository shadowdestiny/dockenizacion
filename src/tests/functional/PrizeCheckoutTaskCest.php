<?php


use EuroMillions\tests\helpers\mothers\PlayConfigMother;

class PrizeCheckoutTaskCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * @param FunctionalTester $I
     * @group active
     */
    public function winnersAreAwarded(FunctionalTester $I, $scenario)
    {
        $scenario->skip();
        /** @var \EuroMillions\web\entities\User $user */
        $user = $I->setRegisteredUser();
        $playConfig = PlayConfigMother::aPlayConfigSetForUser($user)->build();
        $play_config_array = $playConfig->toArray();
        $I->haveInDatabase('play_configs', $play_config_array);

        $I->runShellCommand('php '.__DIR__.'/../../apps/cli-test.php prizeCheckout update');

        $I->seeInDatabase('user', ['id' => $user->getId(), 'show_modal_winning' => 1]);
    }
}
