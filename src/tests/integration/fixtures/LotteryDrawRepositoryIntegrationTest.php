<?php
namespace tests\integration\fixtures;

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
     * @dataProvider getLotteryAndExpectedJackpot
     */
    public function test_getLastJackpot_called_returnLastJackpotOfSelectedLottery($date, $lotteryName, $expectedJackpot)
    {
        $actual = $this->sut->getLastJackpot($lotteryName, $date);
        $this->assertEquals($expectedJackpot, $actual);
    }

    public function getLotteryAndExpectedJackpot()
    {
        return [
            ['2015-05-22', 'EuroMillions', 193948458252],
            ['2015-05-23', 'EuroMillions', 4150340],
            ['2015-05-24', 'La Primitiva', 2934],
        ];
    }
}