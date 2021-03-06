<?php
/**
 * Created by PhpStorm.
 * User: wilcar
 * Date: 06/03/19
 * Time: 03:01 PM
 */

namespace EuroMillions\tests\integration;

use Phalcon\Di;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\tests\helpers\mothers\BetMother;
use EuroMillions\web\vo\EuroMillionsRegularNumber;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\tests\helpers\mothers\LotteryMother;
use EuroMillions\tests\helpers\mothers\PlayConfigMother;
use EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother;

class BetRepositoryIntegrationCest
{
    protected $betRepository;

    public function _before(\IntegrationTester $I)
    {
        $entityManager = Di::getDefault()->get('entityManager');
        $this->betRepository = $entityManager->getRepository('\EuroMillions\web\entities\Bet');
    }

    /**
     * method getMatchesPlayConfigAndUserFromMegaSenaByDrawDate
     * when matches6Balls
     * should returns51
     * @param IntegrationTester $I
     * @group bet-repository-integration
     */
    public function test_getMatchesPlayConfigAndUserFromMegaSenaByDrawDate_matches6Balls_returns51(\IntegrationTester $I)
    {
        $megaSenaLottery = LotteryMother::aMegaSena();

        $date = '2020-01-01';
        $dateTime = new \DateTime($date);

        $line = new EuroMillionsLine(
            [
                new EuroMillionsRegularNumber(1),
                new EuroMillionsRegularNumber(41),
                new EuroMillionsRegularNumber(44),
                new EuroMillionsRegularNumber(46),
                new EuroMillionsRegularNumber(54),
            ],
            [
                new EuroMillionsLuckyNumber(0),
                new EuroMillionsLuckyNumber(58),
            ]
        );

        $draw = EuroMillionsDrawMother::aMegaSenaDrawWithJackpotAndBreakDown($dateTime)
            ->withId(100000000)
            ->withLottery($megaSenaLottery)
            ->withResult($line)
            ->build();

        $I->haveInDatabase('euromillions_draws', $draw->toArray());

        $user = UserMother::aUserWith50Eur()
            ->withValidated(true)
            ->withAffiliate('no')
            ->build();

        $playConfig = PlayConfigMother::aPlayConfigSetForUser($user)
            ->withId(100)
            ->withLine($line)
            ->withLottery($megaSenaLottery)
            ->withStartDrawDate($dateTime)
            ->withLastDrawDate($dateTime)
            ->build();

        $I->haveInDatabase('play_configs',$playConfig->toArray());

        $bet = BetMother::aSingleBet($playConfig, $draw);
        //$betArray = $bet->toArray();
        //unset($betArray['prize']);
        $bet->setPrize(new \Money\Money(10000, new \Money\Currency('EUR')));
        $I->haveInDatabase('bets', $bet->toArray());

        $result = $this->betRepository->getMatchesPlayConfigAndUserFromMegaSenaByDrawDate($date, 6);

        $I->assertEquals($user->getId(), $result[0]['userId']);
        $I->assertEquals(5, $result[0]['cnt']);
        $I->assertEquals(1, $result[0]['cnt_lucky']);
    }

    /**
     * method getMatchesPlayConfigAndUserFromMegaSenaByDrawDate
     * when matches5Balls
     * should returns50
     * @param IntegrationTester $I
     * @group bet-repository-integration
     */
    public function test_getMatchesPlayConfigAndUserFromMegaSenaByDrawDate_matches5Balls_returns50(\IntegrationTester $I)
    {
        $megaSenaLottery = LotteryMother::aMegaSena();

        $date = '2020-01-01';
        $dateTime = new \DateTime($date);

        $line = new EuroMillionsLine(
            [
                new EuroMillionsRegularNumber(1),
                new EuroMillionsRegularNumber(41),
                new EuroMillionsRegularNumber(44),
                new EuroMillionsRegularNumber(46),
                new EuroMillionsRegularNumber(54),
            ],
            [
                new EuroMillionsLuckyNumber(0),
                new EuroMillionsLuckyNumber(58),
            ]
        );

        $draw = EuroMillionsDrawMother::aMegaSenaDrawWithJackpotAndBreakDown($dateTime)
            ->withId(100000000)
            ->withLottery($megaSenaLottery)
            ->withResult($line)
            ->build();

        $I->haveInDatabase('euromillions_draws', $draw->toArray());

        $user = UserMother::aUserWith50Eur()
            ->withValidated(true)
            ->withAffiliate('no')
            ->build();

        $userLine = new EuroMillionsLine(
            [
                new EuroMillionsRegularNumber(1),
                new EuroMillionsRegularNumber(41),
                new EuroMillionsRegularNumber(44),
                new EuroMillionsRegularNumber(46),
                new EuroMillionsRegularNumber(54),
            ],
            [
                new EuroMillionsLuckyNumber(0),
                new EuroMillionsLuckyNumber(52),
            ]
        );

        $playConfig = PlayConfigMother::aPlayConfigSetForUser($user)
            ->withId(100)
            ->withLine($userLine)
            ->withLottery($megaSenaLottery)
            ->withStartDrawDate($dateTime)
            ->withLastDrawDate($dateTime)
            ->build();

        $I->haveInDatabase('play_configs',$playConfig->toArray());

        $bet = BetMother::aSingleBet($playConfig, $draw);
        $betArray = $bet->toArray();
        unset($betArray['prize']);
        $I->haveInDatabase('bets', $betArray);

        $result = $this->betRepository->getMatchesPlayConfigAndUserFromMegaSenaByDrawDate($date, 6);

        $I->assertEquals($user->getId(), $result[0]['userId']);
        $I->assertEquals(5, $result[0]['cnt']);
        $I->assertEquals(0, $result[0]['cnt_lucky']);
    }

    /**
     * method getMatchesPlayConfigAndUserFromMegaSenaByDrawDate
     * when matches4Balls
     * should returns31
     * @param IntegrationTester $I
     * @group bet-repository-integration
     */
    public function test_getMatchesPlayConfigAndUserFromMegaSenaByDrawDate_matches4Balls_returns31(\IntegrationTester $I)
    {
        $megaSenaLottery = LotteryMother::aMegaSena();

        $date = '2020-01-01';
        $dateTime = new \DateTime($date);

        $line = new EuroMillionsLine(
            [
                new EuroMillionsRegularNumber(1),
                new EuroMillionsRegularNumber(41),
                new EuroMillionsRegularNumber(44),
                new EuroMillionsRegularNumber(46),
                new EuroMillionsRegularNumber(54),
            ],
            [
                new EuroMillionsLuckyNumber(0),
                new EuroMillionsLuckyNumber(58),
            ]
        );

        $draw = EuroMillionsDrawMother::aMegaSenaDrawWithJackpotAndBreakDown($dateTime)
            ->withId(100000000)
            ->withLottery($megaSenaLottery)
            ->withResult($line)
            ->build();

        $I->haveInDatabase('euromillions_draws', $draw->toArray());

        $user = UserMother::aUserWith50Eur()
            ->withValidated(true)
            ->withAffiliate('no')
            ->build();

        $userLine = new EuroMillionsLine(
            [
                new EuroMillionsRegularNumber(1),
                new EuroMillionsRegularNumber(41),
                new EuroMillionsRegularNumber(44),
                new EuroMillionsRegularNumber(45),
                new EuroMillionsRegularNumber(50),
            ],
            [
                new EuroMillionsLuckyNumber(0),
                new EuroMillionsLuckyNumber(58),
            ]
        );

        $playConfig = PlayConfigMother::aPlayConfigSetForUser($user)
            ->withId(100)
            ->withLine($userLine)
            ->withLottery($megaSenaLottery)
            ->withStartDrawDate($dateTime)
            ->withLastDrawDate($dateTime)
            ->build();

        $I->haveInDatabase('play_configs',$playConfig->toArray());

        $bet = BetMother::aSingleBet($playConfig, $draw);
        $betArray = $bet->toArray();
        unset($betArray['prize']);
        $I->haveInDatabase('bets', $betArray);

        $result = $this->betRepository->getMatchesPlayConfigAndUserFromMegaSenaByDrawDate($date, 6);

        $I->assertEquals($user->getId(), $result[0]['userId']);
        $I->assertEquals(3, $result[0]['cnt']);
        $I->assertEquals(1, $result[0]['cnt_lucky']);
    }

    /**
     * method getMatchesPlayConfigAndUserFromMegaSenaByDrawDate
     * when matches3Balls
     * should returnsNoResults
     * @param IntegrationTester $I
     * @group bet-repository-integration
     */
    public function test_getMatchesPlayConfigAndUserFromMegaSenaByDrawDate_matches3Balls_returnsNoResults(\IntegrationTester $I)
    {
        $megaSenaLottery = LotteryMother::aMegaSena();

        $date = '2020-01-01';
        $dateTime = new \DateTime($date);

        $line = new EuroMillionsLine(
            [
                new EuroMillionsRegularNumber(1),
                new EuroMillionsRegularNumber(41),
                new EuroMillionsRegularNumber(44),
                new EuroMillionsRegularNumber(46),
                new EuroMillionsRegularNumber(54),
            ],
            [
                new EuroMillionsLuckyNumber(0),
                new EuroMillionsLuckyNumber(58),
            ]
        );

        $draw = EuroMillionsDrawMother::aMegaSenaDrawWithJackpotAndBreakDown($dateTime)
            ->withId(100000000)
            ->withLottery($megaSenaLottery)
            ->withResult($line)
            ->build();

        $I->haveInDatabase('euromillions_draws', $draw->toArray());

        $user = UserMother::aUserWith50Eur()
            ->withValidated(true)
            ->withAffiliate('no')
            ->build();

        $userLine = new EuroMillionsLine(
            [
                new EuroMillionsRegularNumber(1),
                new EuroMillionsRegularNumber(41),
                new EuroMillionsRegularNumber(43),
                new EuroMillionsRegularNumber(45),
                new EuroMillionsRegularNumber(53),
            ],
            [
                new EuroMillionsLuckyNumber(0),
                new EuroMillionsLuckyNumber(58),
            ]
        );

        $playConfig = PlayConfigMother::aPlayConfigSetForUser($user)
            ->withId(100)
            ->withLine($userLine)
            ->withLottery($megaSenaLottery)
            ->withStartDrawDate($dateTime)
            ->withLastDrawDate($dateTime)
            ->build();

        $I->haveInDatabase('play_configs',$playConfig->toArray());

        $bet = BetMother::aSingleBet($playConfig, $draw);
        $betArray = $bet->toArray();
        unset($betArray['prize']);
        $I->haveInDatabase('bets', $betArray);

        $result = $this->betRepository->getMatchesPlayConfigAndUserFromMegaSenaByDrawDate($date, 6);

        $I->assertEquals(0, count($result));
    }
}