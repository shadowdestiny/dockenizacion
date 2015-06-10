<?php
namespace tests\integration;

use EuroMillions\entities\EuroMillionsResult;
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
            'lottery_results',
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

        $curlWrapper_stub = $this->getCurlWrapperWithJackpotRssResponse();

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
        $this->assertEquals($expected->getJackpot(), $result->getJackpot(), "Jackpot doesn't match");
        $this->assertEquals($expected->getDrawDate(), $result->getDrawDate(), "Draw date doesn't match");
        $this->assertEquals(1, $result->lottery_id, "Lottery id doesn't match");

    }

    /**
     * method updateLastDrawResult
     * when calledWithProperDate
     * should createFieldInDatabase
     */
    public function test_updateLastDrawResult_calledWithProperDate_createFieldInDatabase()
    {
        $lottery_name = 'EuroMillions';
        $date = '2015-06-05';
        $now = $date . ' 22:45:00';

        $curlWrapper_stub = $this->getCurlWrapperWithResultRssResponse();

        $sut = new LotteriesDataService();
        $sut->updateLastDrawResult($lottery_name, new \DateTime($now), $curlWrapper_stub);

        $expected = new EuroMillionsResult();
        $expected->initialize([
            'regular_numbers' => '02,07,08,45,48',
            'lucky_numbers' => '01,09',
        ]);

        $conn = $this->getPDO();
        $result = $conn->query(
            "SELECT lr.*".
            " FROM lottery_results lr INNER JOIN lottery_draws ld ON ld.result_id = lr.id".
            " INNER JOIN lotteries l ON ld.lottery_id = l.id".
            " WHERE lr.lottery = '$lottery_name' AND l.name = '$lottery_name'"
        );
        $lottery_result = $result->fetchObject('\EuroMillions\entities\EuroMillionsResult');

        $this->assertEquals($expected->getRegularNumbers(), $lottery_result->getRegularNumbers(), "Regular numbers don't match");
        $this->assertEquals($expected->getLuckyNumbers(), $lottery_result->getLuckyNumbers(), "Regular numbers don't match");
        $this->assertEquals('EuroMillions', $lottery_result->lottery, "Lottery name not properly set");

        $result = $conn->query(
            "SELECT ld.*".
            " FROM lottery_draws ld WHERE ld.draw_date = '$date'"
        );
        $draw = $result->fetchObject('\EuroMillions\entities\LotteryDraw');
        $this->assertEquals($lottery_result->getId(), $draw->result_id);
    }

}