<?php
namespace tests\unit;

use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\services\external_apis\LotteryApisFactory;
use EuroMillions\web\services\LotteriesDataService;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\ActionResult;
use Money\Currency;
use Money\Money;
use Phalcon\Di;
use tests\base\EuroMillionsResultRelatedTest;
use tests\base\UnitTestBase;

class LotteriesDataServiceUnitTest extends UnitTestBase
{
    use EuroMillionsResultRelatedTest;

    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $lotteryDrawRepositoryDouble;
    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $lotteryRepositoryDouble;
    protected $entityManagerDouble;
    /** @var  LotteryApisFactory|\PHPUnit_Framework_MockObject_MockObject */
    protected $apiFactoryDouble;

    public function setUp()
    {
        parent::setUp();
        $this->apiFactoryDouble =
            $this->getMockBuilder(
                '\EuroMillions\web\services\external_apis\LotteryApisFactory'
            )->getMock();
        $this->lotteryDrawRepositoryDouble =
            $this->getMockBuilder(
                '\EuroMillions\web\repositories\LotteryDrawRepository'
            )->disableOriginalConstructor()->getMock();
        /*$this->lotteryRepositoryDouble =
            $this->getMockBuilder(
                '\Doctrine\Common\Persistence\ObjectRepository'
            )->disableOriginalConstructor()->getMock();*/
        $this->lotteryRepositoryDouble =
            $this->getMockBuilder(
                '\EuroMillions\web\repositories\LotteryRepository'
            )->disableOriginalConstructor()->getMock();

        $this->entityManagerDouble = $this->getEntityManagerDouble();
    }

    /**
     * method updateNextDrawJackpot
     * when calledWithExistingDraw
     * should updateDrawInDatabase
     */
    public function test_updateNextDrawJackpot_calledWithExistingDraw_updateDrawInDatabase()
    {
        $today = '2015-06-02 10:00:00';
        $lottery_name = 'EuroMillions';
        $lottery_draw_in_db = new EuroMillionsDraw();
        $lottery_draw_in_db->initialize([
            'id'    => 3484,
            'draw_date'  => '2015-06-02',
            'jackpot'    => null,
        ]);
        $jackpot = 150000000;

        $this->prepareUpdateNextDrawJackpot($lottery_name, $lottery_draw_in_db, $jackpot);

        $draw_to_persist = clone($lottery_draw_in_db);
        $draw_to_persist->setJackpot(new Money($jackpot, new Currency("EUR")));

        $this->entityManagerDouble->flush()->willReturn();
        $this->entityManagerDouble->persist($draw_to_persist)->shouldBeCalledTimes(1);

        $sut = $this->getSut();
        $sut->updateNextDrawJackpot($lottery_name, new \DateTime($today));
    }

    /**
     * method updateNextDrawJackpot
     * when calledWithNonExistingDraw
     * should createDrawInDatabase
     */
    public function test_updateNextDrawJackpot_calledWithNonExistingDraw_createDrawInDatabase()
    {
        $today = '2015-06-02 10:00:00';
        $lottery_name = 'EuroMillions';
        $lottery_draw_in_db = null;
        $jackpot = 1500000000;

        $lottery = $this->prepareUpdateNextDrawJackpot($lottery_name, $lottery_draw_in_db, $jackpot);

        $draw_to_persist = new EuroMillionsDraw();
        $draw_to_persist->initialize([
            'draw_date'  => new \DateTime('2015-06-02 20:00:00'),
            'jackpot'    => new Money($jackpot, new Currency('EUR')),
            'lottery'    => $lottery
        ]);

        $this->entityManagerDouble->flush()->willReturn();
        $this->entityManagerDouble->persist($draw_to_persist)->shouldBeCalledTimes(1);
        $sut = $this->getSut();
        $sut->updateNextDrawJackpot($lottery_name, new \DateTime($today));
    }

    /**
     * method updateLastDrawResult
     * when calledWithADateDifferentThanADrawDate
     * should getResultsFromPreviousDraw
     */
    public function test_updateLastDrawResult_calledWithADateDifferentThanADrawDate_getResultsFromPreviousDraw()
    {
        $lottery_name = 'EuroMillions';

        $this->prepareLotteryEntity($lottery_name);

        $api_mock = $this->getMockBuilder(
            '\EuroMillions\web\services\external_apis\LoteriasyapuestasDotEsApi'
        )->getMock();

        $this->apiFactoryDouble->expects($this->any())
            ->method('resultApi')
            ->will($this->returnValue($api_mock));

        $euroMillionsDraw_stub = $this->getMockBuilder($this->getEntitiesToArgument('EuroMillionsDraw'))->getMock();
        $this->lotteryDrawRepositoryDouble->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue($euroMillionsDraw_stub));

        $api_mock->expects($this->once())
            ->method('getResultForDate')
            ->with($lottery_name, '2015-06-09')
            ->will($this->returnValue(['regular_numbers'=>[], 'lucky_numbers'=>[]]));
        $this->entityManagerDouble->flush()->willReturn();
        $sut = $this->getSut();
        $sut->updateLastDrawResult($lottery_name, new \DateTime('2015-06-10'));
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
        $this->lotteryDrawRepositoryDouble->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue($expected));

        $sut = $this->getSut();
        $actual = $sut->lastBreakDown($lotteryName,new \DateTime('2015-06-10'));
        $this->assertEquals($expected->success(),$actual->success());
    }

    /**
     * method updateLastBreakDown
     * when calledWithPreviousDateThanNow
     * should persistDataInExistDraw
     */
    public function test_updateLastBreakDown_calledWithPreviousDateThanNow_persistDataInExistDraw()
    {
        $lottery_name = 'EuroMillions';

        $this->prepareLotteryEntity($lottery_name);

        $api_mock = $this->getMockBuilder(
            '\EuroMillions\web\services\external_apis\LoteriasyapuestasDotEsApi'
        )->getMock();

        $this->apiFactoryDouble->expects($this->any())
            ->method('resultApi')
            ->will($this->returnValue($api_mock));

        $euroMillionsDraw_stub = $this->getMockBuilder($this->getEntitiesToArgument('EuroMillionsDraw'))->getMock();
        $this->lotteryDrawRepositoryDouble->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue($euroMillionsDraw_stub));

        $api_mock->expects($this->once())
            ->method('getResultBreakDownForDate')
            ->with($lottery_name, '2015-09-22')
            ->will($this->returnValue(['category_one'=>[],
                                       'category_two'=>[],
                                       'category_three'=>[],
                                       'category_four'=>[],
                                       'category_five'=>[],
                                       'category_six'=>[],
                                       'category_seven'=>[],
                                       'category_eight'=>[],
                                       'category_nine'=>[],
                                       'category_ten'=>[],
                                       'category_eleven'=>[],
                                       'category_twelve'=>[],
                                       'category_thirteen'=>[]
                                      ]
            ));
        $this->entityManagerDouble->flush()->willReturn();
        $sut = $this->getSut();
        $sut->updateLastBreakDown($lottery_name, new \DateTime('2015-09-25'));
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
        $euroMillionsDraw_stub = $this->getMockBuilder($this->getEntitiesToArgument('EuroMillionsDraw'))->getMock();
        $lotteryName = 'EuroMillions';
        $this->prepareLotteryEntity($lotteryName);
        $this->lotteryDrawRepositoryDouble->expects($this->any())
            ->method('getNextDraw')
            ->will($this->returnValue($euroMillionsDraw_stub));
        $sut = $this->getSut($lotteryName);
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
        $this->lotteryDrawRepositoryDouble->expects($this->any())
            ->method('getNextDraw')
            ->will($this->returnValue(null));
        $sut = $this->getSut($lotteryName);
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
        $this->lotteryRepositoryDouble->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue(new Lottery()));
        $expected = [
            'regular_numbers' => [1,2,3,4,5],
            'lucky_numbers' => [7,8],
        ];

        $euro_millions_result = new EuroMillionsLine($this->getRegularNumbers([1,2,3,4,5]), $this->getLuckyNumbers([7,8]));

        $this->lotteryDrawRepositoryDouble->expects($this->any())
            ->method('getLastResult')
            ->will($this->returnValue($euro_millions_result));
        $sut = $this->getSut();
        $actual = $sut->getLastResult('EuroMillions');
        $this->assertEquals($expected, $actual);
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

        $expected = new ActionResult(true,$lottery);
        $this->lotteryRepositoryDouble->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue($lottery));

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
        $this->lotteryRepositoryDouble->expects($this->any())
            ->method('getLotteryByName')
            ->will($this->returnValue(null));
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
        $this->lotteryRepositoryDouble->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue(new Lottery()));

        $this->lotteryDrawRepositoryDouble->expects($this->any())
            ->method('getBreakDownData')
            ->will($this->returnValue($expected));

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
        $this->lotteryRepositoryDouble->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue(new Lottery()));

        $this->lotteryDrawRepositoryDouble->expects($this->any())
            ->method('getBreakDownData')
            ->will($this->returnValue(null));

        $sut = $this->getSut();
        $actual = $sut->getBreakDownDrawByDate('EuroMillions', new \DateTime());
        $this->assertEquals($expected,$actual);
    }

    /**
     * method resultCheck
     * when called
     * should returnServiceActionResultTrueWithProperData
     */
    public function test_resultCheck_called_returnServiceActionResultTrueWithProperData()
    {

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
        $this->lotteryRepositoryDouble->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue($lottery));

        $sut = $this->getSut();
        $actual = $sut->getSingleBetPriceByLottery($lotteryName);
        $this->assertEquals($expected->getSingleBetPrice(),$actual);
    }

    /**
     * method getSingleBetPriceByLottery
     * when calledWithoutSingleBetPrice
     * should returnActionResultFalse
     */
    public function test_getSingleBetPriceByLottery_calledWithoutSingleBetPrice_returnActionResultFalse()
    {
        $expected = new ActionResult(false);
        $lotteryName = 'EuroMillions';
        $this->lotteryRepositoryDouble->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue(null));

        $sut = $this->getSut();
        $actual = $sut->getSingleBetPriceByLottery($lotteryName);
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
        $this->lotteryRepositoryDouble->expects($this->any())
            ->method('findOneBy')
            ->with(['name' => $lottery_name])
            ->will($this->returnValue($lottery));
        return $lottery;
    }

    protected function getBreakDownDataDraw()
    {
        return [
                    [
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
                    ]
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
        $this->lotteryDrawRepositoryDouble->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue($lottery_draw_in_db));
    }

    /**
     * @param $lottery_name
     * @param $jackpot
     */
    protected function prepareJackpotApi($lottery_name, $jackpot)
    {
        $api_stub = $this->getMockBuilder(
            '\EuroMillions\web\services\external_apis\LoteriasyapuestasDotEsApi'
        )->getMock();
        $api_stub->expects($this->any())
            ->method('getJackpotForDate')
            ->with($lottery_name, '2015-06-02')
            ->will($this->returnValue(new Money($jackpot, new Currency('EUR'))));
        $this->apiFactoryDouble->expects($this->any())
            ->method('jackpotApi')
            ->will($this->returnValue($api_stub));
    }

    /**
     * @param $lottery_name
     * @param $lottery_draw_in_db
     * @param $jackpot
     * @return Lottery
     */
    protected function prepareUpdateNextDrawJackpot($lottery_name, $lottery_draw_in_db, $jackpot)
    {
        $lottery = $this->prepareLotteryEntity($lottery_name);
        $this->setLotteryDrawRepositoryResult($lottery_draw_in_db);
        $this->prepareJackpotApi($lottery_name, $jackpot);
        return $lottery;
    }

    /**
     * @return LotteriesDataService
     */
    protected function getSut()
    {
        $this->entityManagerDouble->getRepository('EuroMillions\web\entities\EuroMillionsDraw')->willReturn($this->lotteryDrawRepositoryDouble);
        $this->entityManagerDouble->getRepository('EuroMillions\web\entities\Lottery')->willReturn($this->lotteryRepositoryDouble);
        return new LotteriesDataService($this->entityManagerDouble->reveal(), $this->apiFactoryDouble);
    }
}