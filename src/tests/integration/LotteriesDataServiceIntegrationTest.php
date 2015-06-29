<?php
namespace tests\integration;

use EuroMillions\entities\EuroMillionsDraw;
use EuroMillions\services\LotteriesDataService;
use EuroMillions\vo\EuroMillionsResult;
use Money\Currency;
use Money\Money;
use Phalcon\Di;
use tests\base\EuroMillionsResultRelatedTest;
use tests\base\DatabaseIntegrationTestBase;
use tests\base\LoteriasyapuestasDotEsRelatedTest;

class LotteriesDataServiceIntegrationTest extends DatabaseIntegrationTestBase
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
        $draw_date = new \DateTime('2015-06-05');

        $curlWrapper_stub = $this->getCurlWrapperWithJackpotRssResponse();

        $sut = new LotteriesDataService();
        $sut->updateNextDrawJackpot($lottery_name, new \DateTime('2015-06-04'), $curlWrapper_stub);

        $expected = new EuroMillionsDraw();
        $expected->initialize([
            'draw_date' => $draw_date,
            'jackpot' => new Money(10000000000, new Currency('EUR')),
        ]);
        /** @var EuroMillionsDraw $result */
        $result = $this->getDrawFromDatabase($lottery_name, $draw_date);
        $this->assertEquals($expected->getJackpot(), $result->getJackpot(), "Jackpot doesn't match");
        $this->assertEquals($expected->getDrawDate(), $result->getDrawDate(), "Draw date doesn't match");
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

        $euromillions_draw = $this->getDrawFromDatabase($lottery_name, new \DateTime($date));
        $actual_result = $euromillions_draw->getResult();

        $this->assertEquals($expected->getRegularNumbers(), $actual_result->getRegularNumbers(), "Regular numbers don't match");
        $this->assertEquals($expected->getLuckyNumbers(), $actual_result->getLuckyNumbers(), "Regular numbers don't match");
    }

    /**
     * @param $lotteryName
     * @param $drawDate
     * @return EuroMillionsDraw
     */
    private function getDrawFromDatabase($lotteryName, $drawDate)
    {
        $lottery_repo = $this->entityManager->getRepository(self::ENTITIES_NS . 'Lottery');
        $lottery = $lottery_repo->findOneBy(['name' => $lotteryName]);

        $draw_repo = $this->entityManager->getRepository(self::ENTITIES_NS . 'EuroMillionsDraw');
        /** @var EuroMillionsDraw $euromillions_draw */
        $euromillions_draw = $draw_repo->findOneBy(['lottery' => $lottery->getId(), 'draw_date' => $drawDate]);
        return $euromillions_draw;
    }

}