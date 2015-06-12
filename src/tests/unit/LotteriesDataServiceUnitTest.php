<?php
namespace tests\unit;

use EuroMillions\entities\EuroMillionsResult;
use EuroMillions\entities\Lottery;
use EuroMillions\entities\LotteryDraw;
use EuroMillions\entities\LotteryResult;
use EuroMillions\services\external_apis\LotteryApisFactory;
use EuroMillions\services\LotteriesDataService;
use Phalcon\Di;
use tests\base\UnitTestBase;
use Doctrine\ORM\EntityManager;

class LotteriesDataServiceUnitTest extends UnitTestBase
{
    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $lotteryDrawRepositoryDouble;
    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $lotteryResultRepositoryDouble;
    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $lotteryRepositoryDouble;
    /** @var  EntityManager|\PHPUnit_Framework_MockObject_MockObject */
    protected $entityManagerDouble;
    /** @var  LotteryApisFactory|\PHPUnit_Framework_MockObject_MockObject */
    protected $apiFactoryDouble;

    public function setUp()
    {
        parent::setUp();
        $this->apiFactoryDouble =
            $this->getMockBuilder(
                '\EuroMillions\services\external_apis\LotteryApisFactory'
            )->getMock();
        $this->lotteryDrawRepositoryDouble =
            $this->getMockBuilder(
                '\EuroMillions\repositories\LotteryDrawRepository'
            )->disableOriginalConstructor()->getMock();
        $this->lotteryResultRepositoryDouble =
            $this->getMockBuilder(
                '\Doctrine\Common\Persistence\ObjectRepository'
            )->disableOriginalConstructor()->getMock();
        $this->lotteryRepositoryDouble =
            $this->getMockBuilder(
                '\Doctrine\Common\Persistence\ObjectRepository'
            )->disableOriginalConstructor()->getMock();

        $entity_manager = Di::getDefault()->get('entityManager');
        $this->entityManagerDouble = $entity_manager;
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
        $lottery_draw_in_db = new LotteryDraw();
        $lottery_draw_in_db->initialize([
            'draw_id'    => 3484,
            'draw_date'  => '2015-06-02',
            'jackpot'    => null,
            'message'    => '',
            'big_winner' => ''
        ]);
        $jackpot = 15000001;

        $this->prepareUpdateNextDrawJackpot($lottery_name, $lottery_draw_in_db, $jackpot);

        $draw_to_persist = clone($lottery_draw_in_db);
        $draw_to_persist->setJackpot($jackpot);

        $this->entityManagerDouble->expects($this->once())
            ->method('persist')
            ->with($draw_to_persist);

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
        $jackpot = 15000000;

        $lottery = $this->prepareUpdateNextDrawJackpot($lottery_name, $lottery_draw_in_db, $jackpot);

        $draw_to_persist = new LotteryDraw();
        $draw_to_persist->initialize([
            'draw_date'  => new \DateTime('2015-06-02 20:00:00'),
            'jackpot'    => $jackpot,
            'message'    => '',
            'big_winner' => 0,
            'published'  => 0,
            'lottery'    => $lottery
        ]);

        $this->entityManagerDouble->expects($this->once())
            ->method('persist')
            ->with($draw_to_persist);
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
            '\EuroMillions\services\external_apis\LoteriasyapuestasDotEsApi'
        )->getMock();

        $this->apiFactoryDouble->expects($this->any())
            ->method('resultApi')
            ->will($this->returnValue($api_mock));

        $this->lotteryDrawRepositoryDouble->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue(new LotteryDraw()));

        $api_mock->expects($this->once())
            ->method('getResultForDate')
            ->with($lottery_name, '2015-06-09')
            ->will($this->returnValue(['regular_numbers'=>[], 'lucky_numbers'=>[]]));
        $sut = $this->getSut();
        $sut->updateLastDrawResult($lottery_name, new \DateTime('2015-06-10'));
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
            'regular_numbers' => ['01','02','03','04','05'],
            'lucky_numbers' => ['07','08'],
        ];
        $draw_result = (new EuroMillionsResult())->initialize([
            'regular_numbers' => '01,02,03,04,05',
            'lucky_numbers' => '07,08'
        ]);
        $this->lotteryDrawRepositoryDouble->expects($this->any())
            ->method('getLastResult')
            ->will($this->returnValue($draw_result));
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

    /**
     * @param $lottery_draw_in_db
     */
    protected function setLotteryDrawRepositoryResult($lottery_draw_in_db)
    {
        $this->lotteryDrawRepositoryDouble = $this->getMockBuilder(
            '\EuroMillions\repositories\LotteryDrawRepository'
        )->disableOriginalConstructor()->getMock();
        $this->lotteryDrawRepositoryDouble->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue($lottery_draw_in_db));
    }

    /**
     * @param LotteryResult $lottery_result_in_db
     */
    protected function setLotteryResultRepositoryResult($lottery_result_in_db)
    {
        $this->lotteryResultRepositoryDouble = $this->getMockBuilder(
            '\EuroMillions\repositories\LotteryResultRepository'
        )->disableOriginalConstructor()->getMock();
        $this->lotteryResultRepositoryDouble->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue($lottery_result_in_db));
    }

    /**
     * @param $lottery_name
     * @param $jackpot
     */
    protected function prepareJackpotApi($lottery_name, $jackpot)
    {
        $api_stub = $this->getMockBuilder(
            '\EuroMillions\services\external_apis\LoteriasyapuestasDotEsApi'
        )->getMock();
        $api_stub->expects($this->any())
            ->method('getJackpotForDate')
            ->with($lottery_name, '2015-06-02')
            ->will($this->returnValue($jackpot));
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
        $repository_value_map = [
            ['EuroMillions\entities\LotteryDraw', $this->lotteryDrawRepositoryDouble],
            ['EuroMillions\entities\Lottery', $this->lotteryRepositoryDouble],
            ['EuroMillions\entities\LotteryResult', $this->lotteryResultRepositoryDouble]
        ];
        $this->entityManagerDouble->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValueMap($repository_value_map));
        return new LotteriesDataService($this->entityManagerDouble, $this->apiFactoryDouble);
    }
}