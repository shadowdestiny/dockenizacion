<?php


namespace EuroMillions\tests\unit;


use EuroMillions\shared\config\Namespaces;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\BetService;
use EuroMillions\web\services\external_apis\LotteryValidationCastilloApi;
use EuroMillions\web\vo\EuroMillionsLine;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use EuroMillions\tests\base\EuroMillionsResultRelatedTest;
use EuroMillions\tests\base\LotteryValidationCastilloRelatedTest;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;

class BetServiceUnitTest extends UnitTestBase
{
    use EuroMillionsResultRelatedTest;
    use LotteryValidationCastilloRelatedTest;

    private $playConfigRepository_double;

    private $lotteryService_double;

    private $userRepository_double;

    private $betRepository_double;

    private $logValidationApi_double;

    private $lotteryValidation_double;


    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'PlayConfig' => $this->playConfigRepository_double,
            Namespaces::ENTITIES_NS . 'Bet' => $this->betRepository_double,
            Namespaces::ENTITIES_NS . 'User' => $this->userRepository_double,
            Namespaces::ENTITIES_NS . 'LogValidationApi' => $this->logValidationApi_double
        ];
    }

    public function setUp()
    {
        $this->playConfigRepository_double = $this->getRepositoryDouble('PlayConfigRepository');
        $this->lotteryService_double = $this->getServiceDouble('LotteryService');
        $this->betRepository_double = $this->getRepositoryDouble('BetRepository');
        $this->userRepository_double = $this->getRepositoryDouble('UserRepository');
        $this->logValidationApi_double = $this->getRepositoryDouble('LogValidationApiRepository');
        $this->lotteryValidation_double = $this->prophesize(LotteryValidationCastilloApi::class);
        parent::setUp();
    }


    /**
     * method validation
     * when calledWhenTheresNoBetInDB
     * should returnServiceActionResultTrueAndCreateNewBet
     */
    public function test_validattion_calledWhenTheresNoBetInDB_returnServiceActionResultTrueAndCreateNewBet()
    {
        $expected = new ActionResult(true);
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $this->userRepository_double->find(Argument::any())->willReturn($this->getUser());
        $date = new \DateTime();
        $this->lotteryService_double->getNextDateDrawByLottery('EuroMillions', new \DateTime('2015-09-16 00:00:00'))->willReturn($date);
        $this->betRepository_double->getBetsByDrawDate($date)->willReturn(null);
        $this->callValidationApi(true);
        $this->lotteryValidation_double->getXmlResponse()->willReturn(new \SimpleXMLElement(self::$content_with_ok_result));
        $this->logValidationApi_double->add(Argument::type($this->getEntitiesToArgument('LogValidationApi')))->shouldBeCalled();
        $this->betRepository_double->add(Argument::any())->willReturn(true);
        $this->userRepository_double->add(Argument::any())->willReturn(true);
        $this->iDontCareAboutFlush();
        $sut = $this->getSut();
        $actual = $sut->validation($playConfig,$euroMillionsDraw,$date, new \DateTime('2015-09-16 00:00:00'), $this->lotteryValidation_double->reveal());
        $this->assertEquals($expected,$actual);
    }

    /**
     * method validation
     * when calledWhenTheresNoBetInDB
     * should returnServiceActionResultTrueAndCreateNewBet
     */
    public function test_validation_calledWhenTheresNoBetInDB_returnServiceActionResultTrueAndCreateNewBet()
    {
        $expected = new ActionResult(true);
        $date = new \DateTime();
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $this->userRepository_double->find(Argument::any())->willReturn($this->getUser());
        $this->lotteryService_double->getNextDateDrawByLottery('EuroMillions', new \DateTime('2015-09-16 00:00:00'))->willReturn($date);
        $this->betRepository_double->getBetsByDrawDate($date)->willReturn(null);
        $this->callValidationApi(true);
        $this->lotteryValidation_double->getXmlResponse()->willReturn(new \SimpleXMLElement(self::$content_with_ok_result));
        $this->logValidationApi_double->add(Argument::type($this->getEntitiesToArgument('LogValidationApi')))->shouldBeCalled();
        $this->betRepository_double->add(Argument::any())->willReturn(true);
        $this->userRepository_double->add(Argument::any())->willReturn(true);
        $this->iDontCareAboutFlush();
        $sut = $this->getSut();
        $actual = $sut->validation($playConfig,$euroMillionsDraw, $date, new \DateTime('2015-09-16 00:00:00'), $this->lotteryValidation_double->reveal());
        $this->assertEquals($expected,$actual);
    }



    /**
     * method validation
     * when calledWhenABetForNextDrawYetExists
     * should returnServiceActionResultTrueButNotCreateNewBet
     */
    public function test_bet_calledWhenABetForNextDrawYetExists_returnServiceActionResultTrueButNotCreateNewBet()
    {
        $this->markTestSkipped('A lo mejor no tiene sentido en este momento');
        $expected = new ActionResult(true);
        $today = new \DateTime('2015-09-16 00:00:00');
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $bet = new Bet($playConfig,$euroMillionsDraw);
        $this->userRepository_double->find(Argument::any())->willReturn($this->getUser());
        $this->betRepository_double->getBetsByDrawDate(Argument::any())->willReturn($bet);
        $this->betRepository_double->add(Argument::any())->shouldNotBeCalled();
        $sut = $this->getSut();
        $actual = $sut->validation($playConfig,$euroMillionsDraw, new \DateTime('2015-09-18 00:00:00'), $today);
        $this->assertEquals($expected,$actual);
    }


    /**
     * method validation
     * when calledToValidationApi
     * should returnActionResultTrue
     */
    public function test_validation_calledToValidationApi_returnActionResultTrue()
    {
        $expected = new ActionResult(true);
        list($playConfig, $euroMillionsDraw) = $this->exerciseValidationBet($expected);
        $this->userRepository_double->add(Argument::any())->willReturn(true);
        $this->lotteryValidation_double->getXmlResponse()->willReturn(new \SimpleXMLElement(self::$content_with_ok_result));
        $this->logValidationApi_double->add(Argument::type($this->getEntitiesToArgument('LogValidationApi')))->shouldBeCalled();
        $this->logValidationApi_double->add(Argument::any())->shouldBeCalled();
        $this->iDontCareAboutFlush();
        $sut = $this->getSut();
        $actual = $sut->validation($playConfig,$euroMillionsDraw, new \DateTime(), new \DateTime('2015-09-16 00:00:00'), $this->lotteryValidation_double->reveal());
        $this->assertEquals($expected,$actual);
    }


    /**
     * method validation
     * when calledToValidationApi
     * should returnActionResultFalse
     */
    public function test_validation_calledToValidationApi_returnActionResultFalse()
    {
        $expected = new ActionResult(false);
        list($playConfig, $euroMillionsDraw) = $this->exerciseValidationBet($expected);
        $sut = $this->getSut();
        $actual = $sut->validation($playConfig,$euroMillionsDraw, new \DateTime(), new \DateTime('2015-09-16 00:00:00'), $this->lotteryValidation_double->reveal());
        $this->assertEquals($expected,$actual);

    }

    /**
     * method validation
     * when calledWhenUserWithoutBalance
     * should throwInvalidBalanceException
     */
    public function test_bet_calledWhenUserWithoutBalance_throwInvalidBalanceException()
    {
        $user = UserMother::aUserWithNoMoney()->build();
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $this->userRepository_double->find($user->getId())->willReturn($user);
        $this->setExpectedException('EuroMillions\web\exceptions\InvalidBalanceException');
        $sut = $this->getSut();
        $sut->validation($playConfig,$euroMillionsDraw,new \DateTime());
    }


    /**
     * method validation
     * when called
     * should returnExceptionNoWasPossibleSubstractAmountInUser
     */
    public function test_validation_called_returnExceptionNoWasPossibleSubstractAmountInUser()
    {
        $expected = new ActionResult(false);
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $this->userRepository_double->find(Argument::any())->willReturn($this->getUser());
        $date = new \DateTime();
        $this->betRepository_double->getBetsByDrawDate($date)->willReturn(null);
        $this->callValidationApi(true);
        $this->betRepository_double->add(Argument::any())->willReturn(true);
        $this->userRepository_double->add($this->getUser())->willThrow(new \Exception());
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush()->shouldNotBeCalled();
        $sut = $this->getSut();
        $actual = $sut->validation($playConfig,$euroMillionsDraw, $date, new \DateTime('2015-09-16 00:00:00'), $this->lotteryValidation_double->reveal());
        $this->assertEquals($expected,$actual);
    }


    private function getSut()
    {
        return new BetService(
            $this->getEntityManagerRevealed(),
            $this->lotteryService_double->reveal()
        );
    }

    private function getPlayConfigAndEuroMillionsDraw()
    {
        $user = $this->getUser();
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $euroMillionsDraw = new EuroMillionsDraw();
        $euroMillionsLine = new EuroMillionsLine($this->getRegularNumbers($regular_numbers),
            $this->getLuckyNumbers($lucky_numbers));
        $euroMillionsDraw->createResult($regular_numbers, $lucky_numbers);
        $lottery = new Lottery();
        $lottery->initialize([
            'id'        => 1,
            'name'      => 'EuroMillions',
            'active'    => 1,
            'frequency' => 'freq',
            'draw_time' => 'draw',
            'single_bet_price' => new Money(23500, new Currency('EUR')),
        ]);
        $euroMillionsDraw->setLottery($lottery);
        $playConfig = new PlayConfig();
        $playConfig->initialize([
                'user' => $user,
                'line' => $euroMillionsLine
            ]
        );
        return [$playConfig,$euroMillionsDraw];
    }


    /**
     * @param $expected
     */
    private function callValidationApi($expected)
    {
        $this->lotteryValidation_double->validateBet(Argument::type($this->getEntitiesToArgument('Bet')),
            Argument::type($this->getInterfacesToArgument('ICypherStrategy')),
            Argument::type($this->getVOToArgument('CastilloCypherKey')),
            Argument::type($this->getVOToArgument('CastilloTicketId')),
            Argument::type('\DateTime'),Argument::type($this->getVOToArgument('EuroMillionsLine')))->willReturn(new ActionResult($expected));
    }

    /**
     * @param string $currency
     * @return User
     */
    private function getUser()
    {
        return UserMother::aUserWith500Eur()->build();
    }


    /**
     * @param $expected
     * @return array
     */
    private function exerciseValidationBet($expected)
    {
        list($playConfig, $euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $this->userRepository_double->find(Argument::any())->willReturn($this->getUser());
        $this->lotteryService_double->getNextDateDrawByLottery('EuroMillions', Argument::any())->willReturn(new \DateTime('2016-10-04'));
        $this->callValidationApi($expected);
        return array($playConfig, $euroMillionsDraw);
    }
}