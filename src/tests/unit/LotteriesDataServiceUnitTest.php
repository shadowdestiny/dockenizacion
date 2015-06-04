<?php
namespace tests\unit;

use EuroMillions\entities\Lottery;
use EuroMillions\entities\LotteryDraw;
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
            'draw_id' => 3484,
            'draw_date' => '2015-06-02',
            'jackpot' => null,
            'message' => '',
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
        $sut->updateNextDrawJackpot($lottery_name, $today);
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
        $sut->updateNextDrawJackpot($lottery_name, $today);
    }

    //EMTEST qué ocurre cuando la api no tiene todavía el jackpot


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
        ];
        $this->entityManagerDouble->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValueMap($repository_value_map));
        return new LotteriesDataService($this->entityManagerDouble, $this->apiFactoryDouble);
    }

}