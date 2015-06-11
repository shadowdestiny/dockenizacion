<?php
namespace tests\integration;

use EuroMillions\entities\EuroMillionsResult;
use EuroMillions\entities\Lottery;
use EuroMillions\repositories\LotteryDrawRepository;
use tests\base\RepositoryIntegrationTestBase;

class LotteryDrawRepositoryIntegrationTest extends RepositoryIntegrationTestBase
{
    /** @var  LotteryDrawRepository */
    protected $sut;

    protected function getFixtures()
    {
        return [
            'lotteries',
            'lottery_results',
            'lottery_draws',
        ];
    }

    public function setUp()
    {
        parent::setUp('LotteryDraw');
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
        $this->assertEquals($expectedJackpot, $actual);
    }

    public function getLotteryAndExpectedLastJackpot()
    {
        return [
            ['2015-05-22', 'EuroMillions', 193948458252],
            ['2015-05-23', 'EuroMillions', 4150340],
            ['2015-05-24', 'La Primitiva', 2934],
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
        $this->assertEquals($expectedJackpot, $actual);
    }

    public function getLotteryAndExpectedNextJackpot()
    {
        return [
            ['2015-05-22 22:00:00', 'EuroMillions', 100000000],
            ['2015-05-22 23:00:00', 'La Primitiva', 2934],
            ['2015-05-19 22:00:00', 'EuroMillions', 4150340]
        ];
    }

    /**
     * method getLastResult
     * when called
     * should returnProperValue
     */
    public function test_getLastResult_called_returnProperValue()
    {
        /** @var EuroMillionsResult $actual */
        $lottery = (new Lottery())->initialize([
             'name' => 'EuroMillions',
            'frequency' => 'w0100100',
            'draw_time' => '20:00:00',
        ]);
        $actual = $this->sut->getLastResult($lottery, new \DateTime('2015-06-06'));
        $this->assertEquals('05,09,17,32,34', $actual->getRegularNumbers(), 'Regular numbers don\'t match');
        $this->assertEquals('06,08', $actual->getLuckyNumbers(), 'Lucky numbers don\'t match');
    }

    /**
     * method getLastResult
     * when called
     * should returnProperObjectType
     * @dataProvider getLotteryNamesAndEntity
     */
    public function test_getLastResult_called_returnProperEntity($lotteryName, $entityName)
    {
        $lottery = (new Lottery())->initialize([
            'name' => $lotteryName,
            'frequency' => 'w0100100',
            'draw_time' => '20:00:00',
        ]);

        $actual = $this->sut->getLastResult($lottery, new \DateTime('2015-06-06'));
        $this->assertInstanceOf('\EuroMillions\entities\\'.$entityName, $actual);

    }

    public function getLotteryNamesAndEntity()
    {
        return [
            ['EuroMillions', 'EuroMillionsResult'],
        ];
    }
}