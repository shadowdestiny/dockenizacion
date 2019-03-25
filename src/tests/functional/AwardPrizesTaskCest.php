<?php


use EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother;
use EuroMillions\tests\helpers\mothers\EuroMillionsLineMother;
use EuroMillions\tests\helpers\mothers\PlayConfigMother;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\tests\helpers\mothers\LotteryMother;
use EuroMillions\tests\helpers\mothers\BetMother;
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
            ->withLine(EuroMillionsLineMother::anOtherPowerBallLine())
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
        $I->runShellCommand('php '.__DIR__.'/../../apps/shared/shared-cli-test.php prizes award');
        $I->canSeeInDatabase('transactions',['entity_type' => 'winnings_received']);
    }

    /**
     * @group tasks
     * @param FunctionalTester $I
     */
    public function EuroJackpotWinnersAreAwarded(FunctionalTester $I)
    {
        $euroJackpotLottery = LotteryMother::anEuroJackpot();
        /** @var \EuroMillions\web\entities\User $user */
        $user = UserMother::aUserWith50Eur()->withValidated(true)->build();
        $line = EuroMillionsLineMother::anEuroJackpotLine();
        $anotherLine = EuroMillionsLineMother::anotherEuroJackpotLine();
        $date = new DateTime('2020-01-01');
        $playConfig = PlayConfigMother::aPlayConfigSetForUser($user)
            ->withLine($anotherLine)
            ->withLottery($euroJackpotLottery)
            ->withStartDrawDate($date)
            ->withLastDrawDate($date)
            ->build();
        $draw = EuroMillionsDrawMother::anEuroJackpotDrawWithJackpotAndBreakDown($date)
            ->withResult($line)->build();
        $bet = BetMother::aSingleBet($playConfig, $draw);
        $bet->setPrize(new \Money\Money(10000, new \Money\Currency('EUR')));

        $I->haveInDatabase('users',$user->toArray());
        $I->haveInDatabase('euromillions_draws', $draw->toArray());
        $I->haveInDatabase('play_configs', $playConfig->toArray());
        $I->haveInDatabase('bets', $bet->toArray());
        $I->runShellCommand('php '.__DIR__.'/../../apps/cli-test.php result start EuroJackpot 2020-01-01');
        $I->runShellCommand('php '.__DIR__.'/../../apps/shared/shared-cli-test.php prizes listen');
        $I->runShellCommand('php '.__DIR__.'/../../apps/shared/shared-cli-test.php prizes award');
        $I->canSeeInDatabase('transactions',['entity_type' => 'winnings_received']);
    }

    /**
     * @group tasks
     * @param FunctionalTester $I
     */
    public function megaSenaWinnersAreAwarded(FunctionalTester $I)
    {
        $megaSenaLottery = LotteryMother::aMegaSena();
        /** @var \EuroMillions\web\entities\User $user */
        $user = UserMother::aUserWith50Eur()
            ->withValidated(true)
            ->withAffiliate(true)
            ->build();
        $line = EuroMillionsLineMother::aMegaSenaLine();
        $date = new DateTime('2020-01-01');
        $playConfig = PlayConfigMother::aPlayConfigSetForUser($user)
            ->withId(1600)
            ->withLine($line)
            ->withLottery($megaSenaLottery)
            ->withStartDrawDate($date)
            ->withLastDrawDate($date)
            ->build();
        $draw = EuroMillionsDrawMother::aMegaSenaDrawWithJackpotAndBreakDown($date)
            ->withId(1500)
            ->withResult($line)->build();
        $bet = BetMother::aSingleBet($playConfig, $draw);
        $bet->setPrize(new \Money\Money(10000, new \Money\Currency('EUR')));

        $I->haveInDatabase('euromillions_draws', $draw->toArray());
        $I->haveInDatabase('play_configs', $playConfig->toArray());
        $I->haveInDatabase('bets', $bet->toArray());
        $I->runShellCommand('php '.__DIR__.'/../../apps/cli-test.php result start MegaSena 2020-01-01');
        $I->runShellCommand('php '.__DIR__.'/../../apps/shared/shared-cli-test.php prizes listen');
        $I->runShellCommand('php '.__DIR__.'/../../apps/shared/shared-cli-test.php prizes award');
        $I->canSeeInDatabase('transactions',['entity_type' => 'winnings_received']);
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

