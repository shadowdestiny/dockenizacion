<?php
namespace tests\integration;

use EuroMillions\entities\Lottery;
use EuroMillions\repositories\LotteryDrawRepository;
use EuroMillions\vo\EuroMillionsLine;
use Money\Currency;
use Money\Money;
use tests\base\RepositoryIntegrationTestBase;

class LotteryDrawRepositoryIntegrationTest extends RepositoryIntegrationTestBase
{
    /** @var  LotteryDrawRepository */
    protected $sut;

    protected function getFixtures()
    {
        return [
            'lotteries',
            'euromillions_draws',
        ];
    }

    public function setUp()
    {
        parent::setUp('EuroMillionsDraw');
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
        $actual = $this->sut->getNextJackpot($lotteryName, new \DateTime($date));
        $this->assertEquals(new Money($expectedJackpot, new Currency('EUR')), $actual);
    }

    public function getLotteryAndExpectedNextJackpot()
    {
        return [
            ['2015-05-22 22:00:00', 'EuroMillions', 10000000000],
            ['2015-05-19 22:00:00', 'EuroMillions', 415034000]
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
             'name' => 'EuroMillions',
            'frequency' => 'w0100100',
            'draw_time' => '20:00:00',
        ]);
        $actual = $this->sut->getLastResult($lottery, new \DateTime('2015-06-06'));
        $this->assertEquals('5,9,17,32,34', $actual->getRegularNumbers(), 'Regular numbers don\'t match');
        $this->assertEquals('6,8', $actual->getLuckyNumbers(), 'Lucky numbers don\'t match');
    }

    /**
     * method getNextDraw
     * when called
     * should returnProperValue
     */
    public function test_getNextDraw_called_returnProperValue()
    {
        /** @var EuroMillionsLine $actual */
        $lottery = (new Lottery())->initialize([
            'name' => 'EuroMillions',
            'frequency' => 'w0100100',
            'draw_time' => '20:00:00',
        ]);
        $date = new \DateTime('2015-10-02 12:00:00');
        $actual = count($this->sut->getNextDraw($lottery,$date));
        $this->assertGreaterThanOrEqual(1,$actual);
    }

}