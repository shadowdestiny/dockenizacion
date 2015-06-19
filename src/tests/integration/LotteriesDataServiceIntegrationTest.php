<?php
namespace tests\integration;

use EuroMillions\entities\EuroMillionsDraw;
use EuroMillions\services\LotteriesDataService;
use EuroMillions\vo\EuroMillionsResult;
use Phalcon\Di;
use tests\base\EuroMillionsResultRelatedTest;
use tests\base\IntegrationTestBase;
use tests\base\LoteriasyapuestasDotEsRelatedTest;

class LotteriesDataServiceIntegrationTest extends IntegrationTestBase
{
    use LoteriasyapuestasDotEsRelatedTest;
    use EuroMillionsResultRelatedTest;

    protected function getFixtures()
    {
        return [
            'lotteries',
            'euromillions_draws',
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

        $expected = new EuroMillionsDraw();
        $expected->initialize([
            'draw_date' => $draw_date,
            'jackpot' => 100000000,
        ]);
        $conn = $this->getPDO();
        $draw = $conn->query("SELECT ld.* FROM euromillions_draws ld INNER JOIN lotteries l ON l.id = ld.lottery_id WHERE l.name='$lottery_name' AND ld.draw_date = '$draw_date'");
        $result = $draw->fetchObject(self::ENTITIES_NS.'EuroMillionsDraw');
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

        $expected = new EuroMillionsResult($this->getRegularNumbers([2,7,8,45,48]),$this->getLuckyNumbers([1,9]));

        $lottery_repo = $this->entityManager->getRepository(self::ENTITIES_NS.'Lottery');
        $lottery = $lottery_repo->findOneBy(['name'=> $lottery_name]);

        $draw_repo = $this->entityManager->getRepository(self::ENTITIES_NS.'EuroMillionsDraw');
        /** @var EuroMillionsDraw $euromillions_draw */
        $euromillions_draw = $draw_repo->findOneBy(['lottery' => $lottery->getId(), 'draw_date'=> new \DateTime($date)]);
        $actual_result = $euromillions_draw->getResult();

        $this->assertEquals($expected->getRegularNumbers(), $actual_result->getRegularNumbers(), "Regular numbers don't match");
        $this->assertEquals($expected->getLuckyNumbers(), $actual_result->getLuckyNumbers(), "Regular numbers don't match");
    }

}