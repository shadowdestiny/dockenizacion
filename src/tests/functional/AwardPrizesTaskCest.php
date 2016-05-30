<?php


use EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother;
use EuroMillions\tests\helpers\mothers\EuroMillionsLineMother;
use EuroMillions\tests\helpers\mothers\PlayConfigMother;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\entities\Bet;

class AwardprizesTaskCest
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
        $I->haveInDatabase('users',$user->toArray());
        $I->haveInDatabase('play_configs', $playConfig->toArray());
        $draw = EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()->build();
        $draw_array = $draw->toArray();
        $I->haveInDatabase('euromillions_draws', $draw_array);
        $bet = new Bet($playConfig, $draw);
        $bet_array = $bet->toArray();
        $I->haveInDatabase('bets', $bet_array);
        $I->runShellCommand('php '.__DIR__.'/../../apps/cli-test.php awardprizes checkout 2016-04-22');
        //$I->seeInShellOutput('i dont know');
        $I->canSeeInDatabase('users', ['id' => $user->getId(), 'show_modal_winning' => 1]);
        $I->canSeeInDatabase('transactions',['user_id' => $user->getId(),'entity_type' => 'big_winning']);
    }
}

