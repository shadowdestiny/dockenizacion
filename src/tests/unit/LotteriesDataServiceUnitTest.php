<?php
namespace EuroMillions\tests\unit;

use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\services\LotteriesDataService;
use EuroMillions\web\vo\EuroMillionsLine;
use Money\Currency;
use Money\Money;
use Phalcon\Di;
use EuroMillions\tests\base\EuroMillionsResultRelatedTest;
use EuroMillions\tests\base\UnitTestBase;
use Prophecy\Argument;

class LotteriesDataServiceUnitTest extends UnitTestBase
{
    use EuroMillionsResultRelatedTest;

    protected $lotteryDrawRepositoryDouble;
    protected $lotteryRepositoryDouble;
    protected $entityManagerDouble;
    protected $apiFactoryDouble;

    public function setUp()
    {
        parent::setUp();
        $this->apiFactoryDouble = $this->prophesize('EuroMillions\web\services\external_apis\LotteryApisFactory');
        $this->lotteryDrawRepositoryDouble = $this->getRepositoryDouble('LotteryDrawRepository');
        $this->lotteryRepositoryDouble = $this->getRepositoryDouble('LotteryRepository');
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

        $api_mock = $this->prophesize('\EuroMillions\web\services\external_apis\LoteriasyapuestasDotEsApi');

        $this->apiFactoryDouble->resultApi(Argument::any())->willReturn($api_mock->reveal());

        $euroMillionsDraw_stub = $this->getEntityDouble('EuroMillionsDraw');
        $this->lotteryDrawRepositoryDouble->getLastDraw(Argument::any())->willReturn($euroMillionsDraw_stub->reveal());


        $api_mock->getResultForDate($lottery_name, '2015-06-09')->willReturn(['regular_numbers'=>[], 'lucky_numbers'=>[]]);
        $this->entityManagerDouble->persist($euroMillionsDraw_stub)->shouldBeCalled();
        $this->entityManagerDouble->flush()->willReturn();
        $sut = $this->getSut();
        $sut->updateLastDrawResult($lottery_name, new \DateTime('2015-06-10'));
    }

    /**
     * method getPriceForNextDraw
     * when called
     * should returnTotalPriceForNextDraw
     */
    public function test_getPriceForNextDraw_called_returnTotalPriceForNextDraw()
    {
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $lottery = new Lottery();
        $lottery->setSingleBetPrice(new Money(250, new Currency('EUR')));
        $sut = $this->getSut();
        $actual = $sut->getPriceForNextDraw($lottery, [$playConfig,$playConfig,$playConfig]);
        $expected = new Money(750,new Currency('EUR'));
        $this->assertEquals($expected,$actual);
    }

    private function getPlayConfigAndEuroMillionsDraw()
    {
        $user = UserMother::aUserWith50Eur()->build();
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $euroMillionsDraw = new EuroMillionsDraw();
        $euroMillionsLine = new EuroMillionsLine($this->getRegularNumbers($regular_numbers),
            $this->getLuckyNumbers($lucky_numbers));
        $euroMillionsDraw->createResult($regular_numbers, $lucky_numbers);
        $lottery = new Lottery();
        $lottery->initialize([
            'id'               => 1,
            'name'             => 'EuroMillions',
            'active'           => 1,
            'frequency'        => 'freq',
            'draw_time'        => 'draw',
            'single_bet_price' => new Money(23500, new Currency('EUR')),
        ]);
        $euroMillionsDraw->setLottery($lottery);
        $playConfig = new PlayConfig();
        $playConfig->initialize([
                'user' => $user,
                'line' => [$euroMillionsLine],
                'startDrawDate' => new \DateTime('2016-03-16 20:00:00'),
                'lastDrawDate' => new \DateTime('2016-03-16 20:00:00')
            ]
        );
        return [$playConfig, $euroMillionsDraw];
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

    /**
     * @param $lottery_name
     * @param $jackpot
     */
    protected function prepareJackpotApi($lottery_name, $jackpot)
    {
        $api_stub = $this->prophesize('EuroMillions\web\services\external_apis\LoteriasyapuestasDotEsApi');
        $api_stub->getJackpotForDate($lottery_name, '2015-06-02')->willReturn(new Money($jackpot, new Currency('EUR')));
        $this->apiFactoryDouble->jackpotApi(Argument::any())->willReturn($api_stub->reveal());
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
        $this->lotteryDrawRepositoryDouble->findOneBy(Argument::any())->willReturn($lottery_draw_in_db);
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
        return new LotteriesDataService($this->entityManagerDouble->reveal(), $this->apiFactoryDouble->reveal());
    }
}