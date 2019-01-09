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
        $bet->setPrize(new \Money\Money(10000, new \Money\Currency('EUR')));
        $bet_array = $bet->toArray();
        $I->haveInDatabase('bets', $bet_array);
        $I->runShellCommand('php '.__DIR__.'/../../apps/cli-test.php awardprizes checkout 2016-04-23');
       // $I->seeInShellOutput('i dont know');
        $I->canSeeInDatabase('users', ['id' => $user->getId(), 'show_modal_winning' => 1]);
        $I->canSeeInDatabase('transactions',['user_id' => $user->getId(),'entity_type' => 'big_winning']);
    }


    /**
     * @group tasks
     * @param FunctionalTester $I
     */
    public function MegaMillionsAndPowerBallWinnersAreAwarded(FunctionalTester $I)
    {
        $powerBallLottery = \EuroMillions\tests\helpers\mothers\LotteryMother::aPowerBall();
        /** @var \EuroMillions\web\entities\User $user */
        $user = UserMother::aUserWith50Eur()->build();
        $playConfig = PlayConfigMother::aPlayConfigSetForUser($user)
            ->withLine(EuroMillionsLineMother::anPowerBallLine())
            ->withLottery($powerBallLottery)
            ->withStartDrawDate(new DateTime('2020-01-01'))
            ->withLastDrawDate(new DateTime('2020-05-01'))
            ->build();
        $draw = EuroMillionsDrawMother::anPowerBallDrawWithJackpotAndBreakDown()->build();
        $draw_array = $draw->toArray();
        $bet = new Bet($playConfig, $draw);
        $bet->setPrize(new \Money\Money(10000, new \Money\Currency('EUR')));
        $bet_array = $bet->toArray();
        $I->haveInDatabase('users',$user->toArray());
        $I->haveInDatabase('play_configs', $playConfig->toArray());
        $I->haveInDatabase('euromillions_draws', $draw_array);
        $I->haveInDatabase('bets', $bet_array);
        $I->runShellCommand('php '.__DIR__.'/../../apps/cli-test.php result start PowerBall 2020-01-09');
        $I->runShellCommand('php '.__DIR__.'/../../apps/shared/shared-cli-test.php prizes listen');
        $winning = $I->grabColumnFromDatabase('users', 'wallet_winnings_amount', [ 'id' => $user->getId()]);
        $I->assertEquals(5700,$winning);
    }


    /**
     * @param FunctionalTester $I
     * @group active
     */
    public function matchNumbersAndStars(FunctionalTester $I)
    {
        /** @var \EuroMillions\web\entities\User $user */
        $user = UserMother::aUserWith50Eur()->build();
        $playConfig = PlayConfigMother::aPlayConfigSetForUser($user)
            ->withLineFromArrays([1,2,3,40,45],[1,8])
            ->withStartDrawDate(new DateTime('2016-01-01'))
            ->withLastDrawDate(new DateTime('2016-05-01'))
            ->build();
        $I->haveInDatabase('users',$user->toArray());
        $I->haveInDatabase('play_configs', $playConfig->toArray());
        $draw = EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()->build();
        $draw_array = $draw->toArray();
        $I->haveInDatabase('euromillions_draws', $draw_array);
        $bet = new Bet($playConfig, $draw);
        $bet->setId(1);
        $bet->setPrize(new \Money\Money(10000, new \Money\Currency('EUR')));
        $bet_array = $bet->toArray();
        $I->haveInDatabase('bets', $bet_array);
        $I->runShellCommand('php '.__DIR__.'/../../apps/cli-test.php awardprizes checkout 2016-04-23');
       // $I->seeInShellOutput('i dont know');
        $I->canSeeInDatabase('bets',  ['match_numbers' => '1,2,3,0,0', 'match_stars' => '1,0']);

    }


}

