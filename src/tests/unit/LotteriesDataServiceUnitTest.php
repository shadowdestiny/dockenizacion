<?php
namespace tests\unit;

use EuroMillions\entities\Lottery;
use EuroMillions\entities\LotteryDraw;
use EuroMillions\services\LotteriesDataService;
use Phalcon\Di;
use tests\base\LotteryDotIeEuromillionsRelatedTest;
use tests\base\UnitTestBase;

class LotteriesDataServiceUnitTest extends UnitTestBase
{
    use LotteryDotIeEuromillionsRelatedTest;

    /** @var  LotteriesDataService */
    protected $sut;

    protected $lotteryDrawRepositoryDouble;
    protected $lotteryRepositoryDouble;
    protected $entityManagerDouble;

    public function setUp()
    {
        parent::setUp();

        $this->lotteryDrawRepositoryDouble = $this->getMockBuilder('\EuroMillions\repositories\LotteryDrawRepository')->disableOriginalConstructor()->getMock();
        $this->lotteryRepositoryDouble = $this->getMockBuilder('\Doctrine\Common\Persistence\ObjectRepository')->disableOriginalConstructor()->getMock();
        $this->entityManagerDouble = Di::getDefault()->get('entityManager');
        $repository_value_map = [
            ['EuroMillions\entities\LotteryDraw', $this->lotteryDrawRepositoryDouble],
            ['EuroMillions\entities\Lottery', $this->lotteryRepositoryDouble],
        ];
        $this->entityManagerDouble->method('getRepository')
            ->will($this->returnValueMap($repository_value_map));
        $this->sut = new LotteriesDataService($this->entityManagerDouble);
    }

    /**
     * method updateNextDrawJackpot
     * when calledWithNonExistingDraw
     * should createDrawInDatabase
     */
    public function test_updateNextDrawJackpot_calledWithNonExistingDraw_createDrawInDatabase()
    {
        $today = '2015-06-02 10:00:00';
        $lottery = new Lottery();
        $lottery_name = 'EuroMillions';
        $lottery->initialize([
            'id'        => 1,
            'name'      => $lottery_name,
            'active'    => 1,
            'frequency' => 'w0100100',
            'draw_time' => '20:00:00'
        ]);

        $draw_to_persist = new LotteryDraw();
        $draw_to_persist->initialize([
            'draw_date' => '2015-06-02',
            'jackpot'   => null,
            'message' => null,
            'big_winner' => 0,
            'published' => 0,
            'lottery' => $lottery
        ]);

        $this->lotteryRepositoryDouble->expects($this->any())
            ->method('findOneBy')
            ->with(['name' => $lottery_name])
            ->will($this->returnValue($lottery));


        $lotteryDrawRepository_double = $this->getMockBuilder(
            '\EuroMillions\repositories\LotteryDrawRepository'
        )->disableOriginalConstructor()->getMock();
        $lotteryDrawRepository_double->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue(null));
        $this->entityManagerDouble->expects($this->once())
            ->method('persist')
            ->with($draw_to_persist);
        $this->sut->updateNextDrawJackpot($lottery_name, $today);
    }

}