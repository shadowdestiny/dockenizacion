<?php
namespace EuroMillions\tests\unit;


use EuroMillions\tests\helpers\mothers\EuroMillionsLineMother;
use EuroMillions\tests\helpers\mothers\PlayConfigMother;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\exceptions\DataMissingException;
use EuroMillions\web\services\LotteryService;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\EuroMillionsJackpot;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\shared\vo\results\ActionResult;
use Money\Currency;
use Money\Money;
use Phalcon\Di;
use EuroMillions\tests\base\EuroMillionsResultRelatedTest;
use EuroMillions\tests\base\UnitTestBase;
use Prophecy\Argument;

class LotteryServiceUnitTest extends UnitTestBase
{
    use EuroMillionsResultRelatedTest;

    protected $lotteryDrawRepositoryDouble;
    protected $lotteryRepositoryDouble;
    protected $entityManagerDouble;
    protected $lotteriesDataServiceDouble;
    protected $userServiceDouble;
    protected $betService_double;
    protected $emailService_double;
    protected $userNotoificationsService_double;


    public function setUp()
    {
        parent::setUp();
        $this->lotteryDrawRepositoryDouble = $this->getRepositoryDouble('LotteryDrawRepository');
        $this->lotteryRepositoryDouble = $this->getRepositoryDouble('LotteryRepository');
        $this->entityManagerDouble = $this->getEntityManagerDouble();
        $this->lotteriesDataServiceDouble = $this->getServiceDouble('LotteriesDataService');
        $this->userServiceDouble = $this->getServiceDouble('UserService');
        $this->betService_double = $this->getServiceDouble('BetService');
        $this->emailService_double = $this->getServiceDouble('EmailService');
        $this->userNotoificationsService_double = $this->getServiceDouble('UserNotificationsService');
    }

    /**
     * method lastBreakDown
     * when called
     * should returnServiceActionResultTrueWithDraw
     */
    public function test_lastBreakDown_called_returnServiceActionResultTrueWithDraw()
    {
        $lotteryName = 'EuroMillions';
        $this->prepareLotteryEntity($lotteryName);
        $expected = new ActionResult(true);
        $this->lotteryDrawRepositoryDouble->findOneBy(Argument::any())->willReturn($expected);
        $sut = $this->getSut();
        $actual = $sut->lastBreakDown($lotteryName,new \DateTime('2015-06-10'));
        $this->assertEquals($expected->success(),$actual->success());
    }

    /**
     * method getTimeToNextDraw
     * when called
     * should returnProperResult
     * @dataProvider getTimesAndExpectedDiffs
     */
    public function test_getTimeToNextDraw_called_returnProperResult($now, $expectedDiffDays, $expectedDiffHours, $expectedDiffMinutes)
    {
        $lottery_name = 'EuroMillions';
        $this->prepareLotteryEntity($lottery_name);
        $sut = $this->getSut();
        /** @var \DateInterval $actual_diff */
        $actual_diff = $sut->getTimeToNextDraw($lottery_name, new \DateTime($now));
        $this->assertEquals(
            [$expectedDiffDays, $expectedDiffHours, $expectedDiffMinutes],
            [$actual_diff->d, $actual_diff->h, $actual_diff->i]
        );
    }

    /**
     * method getNextDateDrawByLottery
     * when called
     * should returnLotteryNextDrawDate
     */
    public function test_getNextDateDrawByLottery_called_returnLotteryNextDrawDate()
    {
        $lottery_name = 'EuroMillions';
        $this->prepareLotteryEntity($lottery_name);

        $sut = $this->getSut();
        $actual = $sut->getNextDateDrawByLottery($lottery_name, new \DateTime("2015-09-16 19:00:00"));
        $expected = new \DateTime("2015-09-18 20:00:00");
        $this->assertEquals($expected, $actual);
    }

    /**
     * method getNextDrawByLottery
     * when called
     * should returnServiceActionResultTrueWithEuroMillionsDrawInstance
     */
    public function test_getNextDrawByLottery_called_returnServiceActionResultTrueWithEuroMillionsDrawInstance()
    {
        $lotteryName = 'EuroMillions';
        $this->prepareLotteryEntity($lotteryName);
        $this->lotteryDrawRepositoryDouble->getNextDraw(Argument::any(), Argument::any())->willReturn($this->getEntityDouble('EuroMillionsDraw')->reveal());
        $sut = $this->getSut();
        $actual = $sut->getNextDrawByLottery($lotteryName);
        $this->assertInstanceOf($this->getEntitiesToArgument('EuroMillionsDraw'),$actual->getValues());
    }

    /**
     * method getNextDrawByLottery
     * when calledAndEuroMillionsDrawReturnedIsEmpty
     * should returnServiceActionResultFalse
     */
    public function test_getNextDrawByLottery_calledAndEuroMillionsDrawReturnedIsEmpty_returnServiceActionResultFalse()
    {
        $expected = new ActionResult(false);
        $lotteryName = 'EuroMillions';
        $this->prepareLotteryEntity($lotteryName);
        $this->lotteryDrawRepositoryDouble->getNextDraw(Argument::any(), Argument::any())->willReturn(null);
        $sut = $this->getSut();
        $actual = $sut->getNextDrawByLottery($lotteryName);
        $this->assertEquals($expected,$actual);
    }


    /**
     * method getLastResult
     * when called
     * should returnArrayWithContentsOfRepositoryREsult
     */
    public function test_getLastResult_called_returnArrayWithContentsOfRepositoryREsult()
    {
        $this->lotteryRepositoryDouble->getLotteryByName(Argument::any())->willReturn(new Lottery());
        $expected = [
            'regular_numbers' => [1,2,3,4,5],
            'lucky_numbers' => [7,8],
        ];

        $euro_millions_result = new EuroMillionsLine($this->getRegularNumbers([1,2,3,4,5]), $this->getLuckyNumbers([7,8]));

        $this->lotteryDrawRepositoryDouble->getLastResult(Argument::any())->willReturn($euro_millions_result);
        $sut = $this->getSut();
        $actual = $sut->getLastResult('EuroMillions');
        $this->assertEquals($expected, $actual);
    }

    /**
     * method getLastResult
     * when calledWithoutResultInDataBase
     * should tryAndGetLastResultFromApi
     */
    public function test_getLastResult_calledWithoutResultInDataBase_tryAndGetLastResultFromApi()
    {
        $lottery_name = 'EuroMillions';
        $lottery = new Lottery();
        $line = EuroMillionsLineMother::anEuroMillionsLine();
        $expected['regular_numbers'] = [1,2,3,4,5];
        $expected['lucky_numbers'] = [1,2];
        $this->lotteryRepositoryDouble->getLotteryByName($lottery_name)->willReturn($lottery);
        $this->lotteryDrawRepositoryDouble->getLastResult($lottery)->willThrow(new DataMissingException());
        $this->lotteriesDataServiceDouble->updateLastDrawResult($lottery_name)->shouldBeCalled()->willReturn($line);
        $sut = $this->getSut();
        $actual = $sut->getLastResult($lottery_name);
        self::assertEquals($expected, $actual);
    }

    public function getTimesAndExpectedDiffs()
    {
        return [
            ['2015-06-08 05:01:14', 1, 14, 58],
            ['2015-06-08 11:01:14', 1, 8, 58],
            ['2015-06-09 11:01:14', 0, 8, 58],
            ['2015-06-09 19:02:13', 0, 0, 57],
            ['2015-06-09 20:00:00', 3, 0, 0],
            ['2015-06-09 20:01:01', 2, 23, 58]
        ];
    }

    /**
     * method getLastDrawDate
     * when called
     * should returnLotteryLastDrawDate
     */
    public function test_getLastDrawDate_called_returnLotteryLastDrawDate()
    {
        $lottery_name = 'EuroMillions';
        $this->prepareLotteryEntity($lottery_name);

        $sut = $this->getSut();
        $actual = $sut->getLastDrawDate($lottery_name, new \DateTime("2015-06-12 19:00:00"));
        $expected = new \DateTime("2015-06-09 20:00:00");
        $this->assertEquals($expected, $actual);
    }


    /**
     * method getLotteryConfigByName
     * when called
     * should returnServiceActionResultTrueWithLotteryConfig
     */
    public function test_getLotteryConfigByName_called_returnServiceActionResultTrueWithLotteryConfig()
    {
        $lotteryName = 'EuroMillions';

        $lottery = new Lottery();
        $lottery->initialize([
            'id'        => 1,
            'name'      => $lotteryName,
            'active'    => 1,
            'frequency' => 'w0100100',
            'draw_time' => '20:00:00'
        ]);

        $expected = $lottery;
        $this->lotteryRepositoryDouble->findOneBy(Argument::any())->willReturn($lottery);

        $sut = $this->getSut();
        $actual = $sut->getLotteryConfigByName($lotteryName);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getLotteryConfigByName
     * when calledWithWorngNameLottery
     * should returnServiceActionResultFalse
     */
    public function test_getLotteryConfigByName_calledWithWorngNameLottery_returnServiceActionResultFalse()
    {
        $lotteryName = 'EuroMillions2';
        $expected = new ActionResult(false,'Lottery unknown');
        $this->lotteryRepositoryDouble->findOneBy(['name'=>$lotteryName])->willReturn(null);
        $sut = $this->getSut();
        $actual = $sut->getLotteryConfigByName($lotteryName);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getBreakDownDrawByDate
     * when calledWithValidData
     * should returnServiceActionResultTrueWithBreakDownDataDraw
     */
    public function test_getBreakDownDrawByDate_calledWithValidData_returnServiceActionResultTrueWithBreakDownDataDraw()
    {
        $expected = new ActionResult(true,new EuroMillionsDrawBreakDown($this->getBreakDownDataDraw()));
        $this->lotteryRepositoryDouble->findOneBy(Argument::any())->willReturn(new Lottery());

        $this->lotteryDrawRepositoryDouble->getBreakDownData(Argument::any())->willReturn($expected);

        $sut = $this->getSut();
        $actual = $sut->getBreakDownDrawByDate('EuroMillions', new \DateTime("2015-06-06 20:00:00"));
        $this->assertEquals($expected,$actual->getValues());
    }

    /**
     * method getBreakDownDrawByDate
     * when calledWithValidData
     * should returnServiceActionResultFalseWithoutBreakDownDataDraw
     */
    public function test_getBreakDownDrawByDate_calledWithValidData_returnServiceActionResultFalseWithoutBreakDownDataDraw()
    {
        $expected = new ActionResult(false);
        $this->lotteryRepositoryDouble->findOneBy(Argument::any())->willReturn(new Lottery());

        $this->lotteryDrawRepositoryDouble->getBreakDownData(Argument::any())->willReturn(null);

        $sut = $this->getSut();
        $actual = $sut->getBreakDownDrawByDate('EuroMillions', new \DateTime());
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getSingleBetPriceByLottery
     * when called
     * should returnSingleBetPriceMoney
     */
    public function test_getSingleBetPriceByLottery_called_returnSingleBetPriceMoney()
    {
        $lottery = new Lottery();
        $lottery->setSingleBetPrice(new Money(1000, new Currency('EUR')));
        $expected = $lottery;
        $lotteryName = 'EuroMillions';
        $this->lotteryRepositoryDouble->findOneBy(Argument::any())->willReturn($lottery);

        $sut = $this->getSut();
        $actual = $sut->getSingleBetPriceByLottery($lotteryName);
        $this->assertEquals($expected->getSingleBetPrice(),$actual);
    }

    /**
     * method getSingleBetPriceByLottery
     * when calledWithoutSingleBetPrice
     * should throw
     */
    public function test_getSingleBetPriceByLottery_calledWithoutSingleBetPrice_returnActionResultFalse()
    {
        $this->setExpectedException('RuntimeException');
        $lotteryName = 'EuroMillions';
        $this->lotteryRepositoryDouble->findOneBy(Argument::any())->willReturn(null);

        $sut = $this->getSut();
        $sut->getSingleBetPriceByLottery($lotteryName);
    }

    /**
     * method getRecurrentDrawDates
     * when calledWitIterationNum
     * should returnArrayDrawDates
     */
    public function test_getRecurrentDrawDates_calledWitIterationNum_returnArrayDrawDates()
    {
        $lottery = new Lottery();
        $lottery->initialize([
            'id'        => 1,
            'name'      => 'EuroMillions',
            'active'    => 1,
            'frequency' => 'w0100100',
            'draw_time' => '20:00:00'
        ]);
        $now = new \DateTime('2015-11-25');

        $expected = [
            ['0'=> '27 Nov 2015#5'],
            ['0'=> '01 Dec 2015#2'],
            ['0' => '04 Dec 2015#5'],
            ['0' => '08 Dec 2015#2'],
            ['0' => '11 Dec 2015#5']
        ];
        $this->lotteryRepositoryDouble->findOneBy(['name' => 'EuroMillions'])->willReturn($lottery);

        $sut = $this->getSut();
        $actual = $sut->getRecurrentDrawDates('EuroMillions',5,$now);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getNextJackpot
     * when called
     * should returnRepositoryResult
     */
    public function test_getNextJackpot_called_returnRepositoryResult()
    {
        $lottery_name = 'EuroMillions';
        $lottery = new Lottery();
        $this->lotteryRepositoryDouble->getLotteryByName($lottery_name)->willReturn($lottery);
        $expected = EuroMillionsJackpot::fromAmountIncludingDecimals(10392490428902);
        $this->lotteryDrawRepositoryDouble->getNextJackpot($lottery)->willReturn($expected);
        $sut = $this->getSut();
        $actual = $sut->getNextJackpot($lottery_name);
        self::assertEquals($expected, $actual);
    }

    /**
     * method getNextJackpot
     * when dataMissingExceptionIsRaised
     * should tryToUpdateNextJackpotAndReturnJackpot
     */
    public function test_getNextJackpot_dataMissingExceptionIsRaised_tryToUpdateNextJackpotAndReturnJackpot()
    {
        $expected = EuroMillionsJackpot::fromAmountIncludingDecimals(10392490428902);
        $lottery_name = 'EuroMillions';
        $this->lotteryRepositoryDouble->getLotteryByName($lottery_name)->willReturn(new Lottery());
        $this->lotteryDrawRepositoryDouble->getNextJackpot(Argument::any())->willThrow(new DataMissingException());
        $this->lotteriesDataServiceDouble->updateNextDrawJackpot($lottery_name)->shouldBeCalled()->willReturn($expected);
        $sut = $this->getSut();
        $actual = $sut->getNextJackpot($lottery_name);
        self::assertEquals($expected, $actual);
    }

    /**
     * method placeBetForNextDraw
     * when called
     * should createBetForNextDraw
     */
    public function test_placeBetForNextDraw_called_createBetForNextDraw()
    {
        $this->markTestSkipped('Comprobar si hay que eliminar');
        $lottery = $this->prepareLotteryEntity('EuroMillions');
        $date = new \DateTime('2015-09-22');

        $user = UserMother::aUserWith50Eur()->build();

        $valid_play_config1 = PlayConfigMother::aPlayConfig()->withUser($user->getId())->withId(1)->build();
        $valid_play_config2 = PlayConfigMother::aPlayConfig()->withUser($user->getId())->withId(2)->build();
        $invalid_play_config1 = PlayConfigMother::aPlayConfig()->withUser($user->build())->withId(3)->withNoActive()->build();

        $playConfig = [
            $valid_play_config1,
            $invalid_play_config1,
            $valid_play_config2
        ];

        $users = [
            $user->withPlayConfigsCollection($playConfig)->build(),
        ];
        $this->userServiceDouble->getUsersWithPlayConfigsForNextDraw($lottery)->willReturn($users);

        $this->userServiceDouble->getPriceForNextDraw($lottery, [$valid_play_config1, $valid_play_config2])->shouldBeCalled();

        $sut = $this->getSut();
        $actual = $sut->placeBetForNextDraw($lottery,$date);
    }

    /**
     * method getLotteriesOrderedByNextDrawDate
     * when called
     * should returnLotteriesOrderedProperty
     */
    public function test_getLotteriesOrderedByNextDrawDate_called_returnLotteriesOrderedProperty()
    {
        $lottery = new Lottery();
        $lottery->initialize([
            'id'        => 1,
            'name'      => 'EuroMillions',
            'active'    => 1,
            'frequency' => 'w0100100',
            'draw_time' => '20:00:00'
        ]);
        $lotteryOne = new Lottery();
        $lotteryOne->initialize([
            'id'        => 1,
            'name'      => 'Bonoloto',
            'active'    => 1,
            'frequency' => 'w1111110',
            'draw_time' => '20:00:00'
        ]);
        $this->lotteryRepositoryDouble->findAll()->willReturn([$lottery,$lotteryOne]);
        $sut = $this->getSut();
        $actual = $sut->getLotteriesOrderedByNextDrawDate(new \DateTime('2015-09-21'));
        $expected = [
            '2015-09-21 20:00:00' => $lotteryOne,
            '2015-09-22 20:00:00' => $lottery
        ];
        $this->assertEquals($expected,$actual);
    }

    /**
     * @param $lottery_name
     * @return Lottery
     */
    protected function prepareLotteryEntity($lottery_name)
    {
        $lottery = new Lottery();
        $lottery->initialize([
            'id'        => 1,
            'name'      => $lottery_name,
            'active'    => 1,
            'frequency' => 'w0100100',
            'draw_time' => '20:00:00'
        ]);
        $this->lotteryRepositoryDouble->findOneBy(['name' => $lottery_name])->willReturn($lottery);
        return $lottery;
    }

    protected function getBreakDownDataDraw()
    {
        return [
                        'category_one' => ['5 + 2', '000', '0'],
                        'category_two' => ['5 + 1', '29392657', '9'],
                        'category_three' => ['5 + 0', '8817797', '10'],
                        'category_four' => ['4 + 2', '668015', '66'],
                        'category_five' => ['4 + 1', '27516', '1.402'],
                        'category_six' => ['4 + 0', '13149', '2.934'],
                        'category_seven' => ['3 + 2', '6087', '4.527'],
                        'category_eight' => ['2 + 2', '1893', '66.973'],
                        'category_nine' => ['3 + 1', '1673', '72.488'],
                        'category_ten' => ['3 + 0', '1341', '152.009'],
                        'category_eleven' => ['1 + 2', '998', '358.960'],
                        'category_twelve' => ['2 + 1', '852', '1.138.617'],
                        'category_thirteen' => ['2 + 0', '415', '2.390.942'],

        ];
    }

    /**
     * @param $lottery_draw_in_db
     */
    protected function setLotteryDrawRepositoryResult($lottery_draw_in_db)
    {
        $this->lotteryDrawRepositoryDouble = $this->getMockBuilder(
            '\EuroMillions\web\repositories\LotteryDrawRepository'
        )->disableOriginalConstructor()->getMock();
        $this->lotteryDrawRepositoryDouble->findOneBy(Argument::any())->willReturn($lottery_draw_in_db);
    }

    /**
     * @return LotteryService
     */
    protected function getSut()
    {
        $this->entityManagerDouble->getRepository('EuroMillions\web\entities\EuroMillionsDraw')->willReturn($this->lotteryDrawRepositoryDouble->reveal());
        $this->entityManagerDouble->getRepository('EuroMillions\web\entities\Lottery')->willReturn($this->lotteryRepositoryDouble->reveal());
        return new LotteryService($this->entityManagerDouble->reveal(),
                                  $this->lotteriesDataServiceDouble->reveal(),
                                  $this->userServiceDouble->reveal(),
                                  $this->betService_double->reveal(),
                                  $this->emailService_double->reveal(),
                                  $this->userNotoificationsService_double->reveal()
                                );
    }
}