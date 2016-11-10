<?php
namespace EuroMillions\tests\unit;

use EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother;
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
    protected $lotteriesDataServiceDouble;

    public function setUp()
    {
        parent::setUp();
        $this->apiFactoryDouble = $this->prophesize('EuroMillions\web\services\external_apis\LotteryApisFactory');
        $this->lotteryDrawRepositoryDouble = $this->getRepositoryDouble('LotteryDrawRepository');
        $this->lotteryRepositoryDouble = $this->getRepositoryDouble('LotteryRepository');
        $this->entityManagerDouble = $this->getEntityManagerDouble();
        $this->lotteriesDataServiceDouble = $this->getServiceDouble('LotteriesDataService');
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
     * method updateLastBreakdown
     * when calledWithADateDifferentThanADrawDate
     * should getResultsFromPreviousDraw
     */
    public function test_updateLastBreakdown_calledWithADateDifferentThanADrawDate_getResultsFromPreviousDraw()
    {
        $lottery_name = 'EuroMillions';
        $drawDate = new \DateTime('2015-06-10');
        $lastdrawDate = new \DateTime('2015-06-09 20:00:00');
        $api_mock = $this->prophesize('\EuroMillions\web\services\external_apis\LoteriasyapuestasDotEsApi');
        $this->lotteryRepositoryDouble->findOneBy(['name' => $lottery_name])->willReturn($this->prepareLotteryEntity($lottery_name));
        $this->apiFactoryDouble->resultApi(Argument::any())->willReturn($api_mock->reveal());
        $expected = EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()->build();
        $expected->setDrawDate($drawDate);
        $this->lotteryDrawRepositoryDouble->getLastDraw(Argument::any())->willReturn($expected);
        $api_mock->getResultBreakDownForDate($lottery_name, '2015-06-09')->willReturn($this->getBreakDownDataDraw()[0]);
        $this->lotteryDrawRepositoryDouble->findOneBy(['lottery' => $this->prepareLotteryEntity($lottery_name), 'draw_date' => $lastdrawDate])->willReturn($expected);
        $this->entityManagerDouble->flush()->shouldBeCalled();
        $sut = $this->getSut();
        $actual = $sut->updateLastBreakDown($lottery_name, $drawDate);
        $this->assertEquals($expected->getBreakDown(), $actual->getBreakDown());
    }

    /**
     * method updateLastBreakdown
     * when calledWithADateDifferentThanADrawDate
     * should getResultsFromPreviousDraw
     */
    public function test_updateLastBreakdown_isEmpty()
    {
        $lottery_name = 'EuroMillions';
        $drawDate = new \DateTime('2015-06-10');
        $api_mock = $this->prophesize('\EuroMillions\web\services\external_apis\LoteriasyapuestasDotEsApi');
        $this->lotteryRepositoryDouble->findOneBy(['name' => $lottery_name])->willReturn($this->prepareLotteryEntity($lottery_name));
        $this->apiFactoryDouble->resultApi(Argument::any())->willReturn($api_mock->reveal());
        $expected = EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()->build();
        $expected->setDrawDate($drawDate);
        $this->lotteryDrawRepositoryDouble->getLastDraw(Argument::any())->willReturn($expected);
        $api_mock->getResultBreakDownForDate($lottery_name, '2015-06-09')->WillThrow(new \Exception);
        $this->setExpectedException('\Exception');
        $sut = $this->getSut();
        $sut->updateLastBreakDown($lottery_name, $drawDate);
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

    protected function getBreakDownDataDraw()
    {
        return [
            [
                'category_one' => ['5 + 2', '189080000', '0'],
                'category_two' => ['5 + 1', '2939257', '9'],
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
}