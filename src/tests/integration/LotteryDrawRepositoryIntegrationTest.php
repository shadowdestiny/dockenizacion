<?php
namespace tests\integration;

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
            'lottery_draws'
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
        $actual = $this->sut->getNextJackpot($lotteryName, $date);
        $this->assertEquals($expectedJackpot, $actual);
    }

    public function getLotteryAndExpectedNextJackpot()
    {
        return [
            ['2015-05-22', 'EuroMillions', 4150340],
            ['2015-05-22', 'La Primitiva', 2934],
            ['2015-05-19', 'EuroMillions', 4150340]
        ];
    }
}