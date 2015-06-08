<?php
namespace tests\integration;

use EuroMillions\entities\LotteryDraw;
use EuroMillions\services\LotteriesDataService;
use tests\base\IntegrationTestBase;
use tests\base\LoteriasyapuestasDotEsRelatedTest;

class LotteriesDataServiceIntegrationTest extends IntegrationTestBase
{
    use LoteriasyapuestasDotEsRelatedTest;

    protected function getFixtures()
    {
        return [
            'lotteries',
            'lottery_draws',
        ];
    }

    /**
     * method updateNextDrawJackpot
     * when calledWithProperDate
     * should createFieldInDatabase
     */
    public function test_updateNextDrawJackpot_calledWithProperDate_createFieldInDatabase()
    {
        $lottery_name = 'EuroMillions';
        $draw_date = '2015-06-05';

        $curlWrapper_stub = $this->getCurlWrapperWithRssResponse();

        $sut = new LotteriesDataService();
        $sut->updateNextDrawJackpot($lottery_name, new \DateTime('2015-06-04'), $curlWrapper_stub);

        $expected = new LotteryDraw();
        $expected->initialize([
            'draw_date' => $draw_date,
            'jackpot' => 100000000,
        ]);
        $conn = $this->getPDO();
        $draw = $conn->query("SELECT ld.* FROM lottery_draws ld INNER JOIN lotteries l ON l.id = ld.lottery_id WHERE l.name='$lottery_name' AND ld.draw_date = '$draw_date'");
        $result = $draw->fetchObject('\EuroMillions\entities\LotteryDraw');
        $this->assertTrue(count($result) == 1);
        $this->assertEquals($expected->getJackpot(), $result->getJackpot(), "Jackpot doesn't match");
        $this->assertEquals($expected->getDrawDate(), $result->getDrawDate(), "Draw date doesn't match");
        $this->assertEquals(1, $result->lottery_id, "Lottery id doesn't match");

    }

}