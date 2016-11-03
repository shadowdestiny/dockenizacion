<?php


use EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother;
use EuroMillions\tests\helpers\mothers\EuroMillionsLineMother;
use EuroMillions\tests\helpers\mothers\PlayConfigMother;
use EuroMillions\tests\helpers\mothers\RaffleMother;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\entities\Bet;

class RaffleCest
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
    public function winnersAreAwarded(FunctionalTester $I)
    {
        /** @var \EuroMillions\web\entities\User $user */
        $user = UserMother::aUserWith50Eur()->build();
        $playConfig = PlayConfigMother::aPlayConfigSetForUser($user)
            ->withLine(EuroMillionsLineMother::anEuroMillionsLine())
            ->withStartDrawDate(new DateTime('2016-01-01'))
            ->withLastDrawDate(new DateTime('2016-05-01'))
            ->build();
        $I->haveInDatabase('users', $user->toArray());
        $I->haveInDatabase('play_configs', $playConfig->toArray());
        $draw = EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()->build();
        $draw_array = $draw->toArray();
        $I->haveInDatabase('euromillions_draws', $draw_array);
        $bet = new Bet($playConfig, $draw);
        $bet->setPrize(new \Money\Money(10000, new \Money\Currency('EUR')));
        $bet_array = $bet->toArray();
        $I->haveInDatabase('bets', $bet_array);
        $I->runShellCommand('php ' . __DIR__ . '/../../apps/cli-test.php awardprizes checkout 2016-04-23');
        // $I->seeInShellOutput('i dont know');
        $I->canSeeInDatabase('users', ['id' => $user->getId(), 'show_modal_winning' => 1]);
        $I->canSeeInDatabase('transactions', ['user_id' => $user->getId(), 'entity_type' => 'big_winning']);
    }

    /**
     * @param FunctionalTester $I
     * @group active
     */
    public function matchRaffle(FunctionalTester $I)
    {
        //TODO:
        /** @var \EuroMillions\web\entities\User $user */
        $user = UserMother::aUserWith50Eur()->build();
        $playConfig = PlayConfigMother::aPlayConfigSetForUser($user)
            ->withLineFromArrays([1, 2, 3, 40, 45], [1, 8])
            ->withStartDrawDate(new DateTime('2016-01-01'))
            ->withLastDrawDate(new DateTime('2016-05-01'))
            ->build();
        $I->haveInDatabase('users', $user->toArray());
        $I->haveInDatabase('play_configs', $playConfig->toArray());
        $draw = EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()->build();
        $draw_array = $draw->toArray();
        $raffle = RaffleMother::anRaffle();
        $I->haveInDatabase('euromillions_draws', $draw_array);
        $I->canSeeInDatabase('euromillions_draws', ['raffle_value' => $raffle]);
        $bet = new Bet($playConfig, $draw);
        $bet->setId(1);
        $bet->setPrize(new \Money\Money(10000, new \Money\Currency('EUR')));
        $bet_array = $bet->toArray();
        $I->haveInDatabase('bets', $bet_array);
        $I->runShellCommand('php ' . __DIR__ . '/../../apps/cli-test.php awardprizes checkout 2016-04-23');
        // $I->seeInShellOutput('i dont know');
        $I->canSeeInDatabase('bets', ['match_numbers' => '1,2,3,0,0', 'match_stars' => '1,0']);
    }
}
