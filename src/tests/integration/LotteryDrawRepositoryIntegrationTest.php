<?php
namespace EuroMillions\tests\integration;

use EuroMillions\shared\config\Namespaces;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\repositories\LotteryDrawRepository;
use EuroMillions\web\repositories\LotteryRepository;
use EuroMillions\web\vo\EuroMillionsJackpot;
use EuroMillions\web\vo\EuroMillionsLine;
use Money\Currency;
use Money\Money;
use EuroMillions\tests\base\RepositoryIntegrationTestBase;

class LotteryDrawRepositoryIntegrationTest extends RepositoryIntegrationTestBase
{
    /** @var  LotteryDrawRepository */
    protected $sut;
    /** @var  LotteryRepository */
    protected $lotteryRepository;


    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->lotteryRepository = $this->entityManager->getRepository('EuroMillions\web\entities\Lottery');
    }

    protected function getFixtures()
    {
        return [
            'lotteries',
            'euromillions_draws',
        ];
    }

    /**
     * method getLastJackpot
     * when called
     * should returnLastJackpotOfSelectedLottery
     * @dataProvider getLotteryAndExpectedLastJackpot
     */
    public function test_getLastJackpot_called_returnLastJackpotOfSelectedLottery($date, $lotteryName, $expectedJackpot)
    {
        $actual = $this->sut->getLastJackpot($lotteryName, $date);
        $this->assertEquals(new Money($expectedJackpot, new Currency('EUR')), $actual);
    }

    public function getLotteryAndExpectedLastJackpot()
    {
        return [
            ['2015-05-22', 'EuroMillions', 19394845825200],
            ['2015-05-23', 'EuroMillions', 415034000],
        ];
    }

    /**
     * method getNextJackpot
     * when called
     * should returnNextJackpotOfSelectedLottery
     * @dataProvider getLotteryAndExpectedNextJackpot
     */
    public function test_getNextJackpot_called_returnNextJackpotOfSelectedLottery($date, $lotteryName, $expectedJackpot)
    {
        $actual = $this->sut->getNextJackpot($this->getLotteryInstance($lotteryName), new \DateTime($date));
        $this->assertEquals(new Money($expectedJackpot, new Currency('EUR')), $actual);
    }

    public function getLotteryAndExpectedNextJackpot()
    {
        return [
            ['2015-05-12 22:00:00', 'EuroMillions', 10344500],
            ['2015-05-15 22:00:00', 'EuroMillions', 19394845825200],
            ['2015-05-16 22:00:00', 'EuroMillions', 19394845825200],
            ['2015-05-19 17:00:00', 'EuroMillions', 19394845825200],
            ['2015-05-22 17:00:00', 'EuroMillions', 415034000]
        ];
    }

    /**
     * method getNextJackpot
     * when calledAndThereIsNotNextDrawWithProperDate
     * should throwException
     * @dataProvider getBadLotteryDatesForNextJackpot
     */
    public function test_getNextJackpot_calledAndThereIsNotNextDrawWithProperDate_throwException($date)
    {
        $this->setExpectedException('EuroMillions\web\exceptions\DataMissingException');
        $this->sut->getNextJackpot($this->getLotteryInstance('EuroMillions'), new \DateTime($date));
    }

    public function getBadLotteryDatesForNextJackpot()
    {
        return [
            ['2015-05-22 22:00:00'], //There's a next draw, but not the correct one
            ['2015-05-23 22:00:00'],
            ['2005-05-22 22:00:00'],
            ['2128-01-22 10:00:00']
        ];
    }


    /**
     * method getLastResult
     * when called
     * should returnProperValue
     */
    public function test_getLastResult_called_returnProperValue()
    {
        /** @var EuroMillionsLine $actual */
        $lottery = (new Lottery())->initialize([
            'name'      => 'EuroMillions',
            'frequency' => 'w0100100',
            'draw_time' => '20:00:00',
        ]);
        $actual = $this->sut->getLastResult($lottery, new \DateTime('2015-06-06'));
        $this->assertEquals('5,9,17,32,34', $actual->getRegularNumbers(), 'Regular numbers don\'t match');
        $this->assertEquals('6,8', $actual->getLuckyNumbers(), 'Lucky numbers don\'t match');
    }

    /**
     * method getLastResult
     * when calledWithoutThePreviousResultInDatabase
     * should throw
     */
    public function test_getLastResult_calledWithoutThePreviousResultInDatabase_throw()
    {
        $this->setExpectedException('EuroMillions\web\exceptions\DataMissingException');
        $this->sut->getNextJackpot($this->getLotteryInstance('EuroMillions'), new \DateTime("2026-10-22"));
    }

    /**
     * method getNextDraw
     * when called
     * should returnProperValue
     */
    public function test_getNextDraw_called_returnProperValue()
    {
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->find(1);
        $date = new \DateTime('2015-10-02 12:00:00');
        $actual = count($this->sut->getNextDraw($lottery, $date));
        $this->assertGreaterThanOrEqual(1, $actual);
    }

    /**
     * method getNextDraw
     * when calledWhenJackpotTaskPowerBall
     * should returnError
     */
    public function test_getNextDraw_calledWhenJackpotTaskPowerBall_returnError()
    {
        $this->markTestSkipped('fail on reproduce error on lottery bug ');
        $draw = new EuroMillionsDraw();

        $lottery = $this->lotteryRepository->getLotteryByName('PowerBall');
        $draw_date= $lottery->getNextDrawDate();

        $draw->initialize([
            'draw_date' => $draw_date,
            'jackpot' => EuroMillionsJackpot::fromAmountIncludingDecimals(4000000000),
            'lottery' => $lottery
        ]);

        $this->entityManager->persist($draw);
        $this->entityManager->flush();

        $actual = $this->sut->getNextDraw($lottery, $draw_date);

        $this->assertNotEquals($draw_date, $actual->getDrawDate());
    }

    /**
     * method getNextDraw
     * when calledWhenJackpotTaskMegaMillions
     * should returnError
     */
    public function test_getNextDraw_calledWhenJackpotTaskMegaMillions_returnError()
    {
        $this->markTestSkipped('fail on reproduce error on lottery bug ');
        $draw = new EuroMillionsDraw();

        $lottery = $this->lotteryRepository->getLotteryByName('Megamillions');
        $draw_date= $lottery->getNextDrawDate();

        $draw->initialize([
            'draw_date' => $draw_date,
            'jackpot' => EuroMillionsJackpot::fromAmountIncludingDecimals(4000000000),
            'lottery' => $lottery
        ]);

        $this->entityManager->persist($draw);
        $this->entityManager->flush();

        $actual = $this->sut->getNextDraw($lottery, $draw_date);

        $this->assertNotEquals($draw_date, $actual->getDrawDate());
    }

    /**
     * method getLastDraw
     * when calledWithPreviousDrawOnDatabase
     * should returnProperEntity
     */
    public function test_getLastDraw_calledWithPreviousDrawOnDatabase_returnProperEntity()
    {
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->find(1);
        $expected = $this->sut->find(8);
        $date = new \DateTime('2015-06-04 12:00:00');
        $actual = $this->sut->getLastDraw($lottery, $date);
        $this->assertEquals($expected, $actual);
    }

    /**
     * method getLastDraw
     * when calledWithoutPreviousDrawOnDatabase
     * should throw
     */
    public function test_getLastDraw_calledWithoutPreviousDrawOnDatabase_throw()
    {
        $this->setExpectedException('EuroMillions\web\exceptions\DataMissingException');
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->find(1);
        $date = new \DateTime('2093-02-02 12:00:00');
        $this->sut->getLastDraw($lottery, $date);
    }


    /**
     * method getDraws
     * when called
     * should returnListOfDraws
     */
    public function test_getDraws_called_returnListOfDraws()
    {
        $this->markTestSkipped('This test don\'t works anymore :( | Fix it? ');

        /** @var EuroMillionsLine $actual */
        $lottery = (new Lottery())->initialize([
            'name'      => 'EuroMillions',
            'frequency' => 'w0100100',
            'draw_time' => '20:00:00',
        ]);
        $actual = $this->sut->getDraws($lottery);
        $this->assertEquals(new \DateTime('2015-10-02 00:00:00'), $actual[1]->getDrawDate());
    }

    /**
     * method giveMeLotteriesOrderedByHeldDate
     * when called
     * should returnListByLotteryOrdered
     */
    public function test_giveMeLotteriesOrderedByHeldDate_called_returnListByLotteryOrdered()
    {
        $this->markTestSkipped('This test don\'t works anymore :( | Fix it? ');

        $expected = [12,11,13,14];
        $actual = $this->sut->giveMeLotteriesOrderedByHeldDate();
        $this->assertEquals($actual[0]->getId(),$expected[0]);
        $this->assertEquals($actual[1]->getId(),$expected[1]);
        $this->assertEquals($actual[2]->getId(),$expected[2]);
        $this->assertEquals($actual[3]->getId(),$expected[3]);
    }


    /**
     * method giveMeBiggestJackpot
     * when called
     * should returnBiggestJackpot
     */
    public function test_giveMeBiggestJackpot_called_returnBiggestJackpot()
    {
        $this->markTestSkipped('Check!');
        $expected = 2001;
        $actual = $this->sut->giveMeBiggestJackpot();
        $this->assertEquals($actual,$expected);
    }




    protected function getEntity()
    {
        return 'EuroMillionsDraw';
    }

    /**
     * @param $lotteryName
     * @return Lottery
     */
    private function getLotteryInstance($lotteryName)
    {
        $lottery_repository = $this->entityManager->getRepository(Namespaces::ENTITIES_NS.'Lottery');
        return $lottery_repository->findOneBy(['name'=> $lotteryName]);
    }
}