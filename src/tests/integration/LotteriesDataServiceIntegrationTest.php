<?php
namespace tests\integration;

use EuroMillions\config\Namespaces;
use EuroMillions\entities\EuroMillionsDraw;
use EuroMillions\services\external_apis\LotteryApisFactory;
use EuroMillions\services\LotteriesDataService;
use EuroMillions\vo\EuroMillionsDrawBreakDown;
use EuroMillions\vo\EuroMillionsDrawBreakDownData;
use EuroMillions\vo\EuroMillionsLine;
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

        $sut = new LotteriesDataService($this->entityManager, new LotteryApisFactory());
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

        $sut = new LotteriesDataService($this->entityManager, new LotteryApisFactory());
        $sut->updateLastDrawResult($lottery_name, new \DateTime($now), $curlWrapper_stub);

        $expected = new EuroMillionsLine($this->getRegularNumbers([2,7,8,45,48]),$this->getLuckyNumbers([1,9]));

        $euromillions_draw = $this->getDrawFromDatabase($lottery_name, new \DateTime($date));
        $actual_result = $euromillions_draw->getResult();

        $this->assertEquals($expected->getRegularNumbers(), $actual_result->getRegularNumbers(), "Regular numbers don't match");
        $this->assertEquals($expected->getLuckyNumbers(), $actual_result->getLuckyNumbers(), "Regular numbers don't match");
    }

    /**
     * method updateLastBreakDown
     * when calledWithProperData
     * should updateFieldInDatabase
     */
    public function test_updateLastBreakDown_calledWithProperData_updateFieldInDatabase()
    {
        $lotteryName = 'EuroMillions';
        $date = '2015-06-05 22:45:00';

        $curlWrapper_stub = $this->getCurlWrapperWithResultRssResponse();
        $sut = new LotteriesDataService($this->entityManager, new LotteryApisFactory());
        $sut->updateLastBreakDown($lotteryName, new \DateTime($date), $curlWrapper_stub);

        $expected = new EuroMillionsDrawBreakDown($this->getBreakDownResult());
        $euromillions_draw = $this->getDrawFromDatabase($lotteryName, new \DateTime($date));
        /** @var EuroMillionsDrawBreakDown $actual_result */
        $actual_result = $euromillions_draw->getBreakDown()->getCategoryOne();
        $this->assertEquals($expected->getCategoryOne(),$actual_result);
    }


    private function getBreakDownResult()
    {
        return [
                [
                    'category_one' => ['5 + 2', '0.00', '0'],
                    'category_two' => ['5 + 1', '293.926.57', '9'],
                    'category_three' => ['5 + 0', '88.177.97', '10'],
                    'category_four' => ['4 + 2', '6.680.15', '66'],
                    'category_five' => ['4 + 1', '275.16', '1.402'],
                    'category_six' => ['4 + 0', '131.49', '2.934'],
                    'category_seven' => ['3 + 2', '60.87', '4.527'],
                    'category_eight' => ['2 + 2', '18.93', '66.973'],
                    'category_nine' => ['3 + 1', '16.73', '72.488'],
                    'category_ten' => ['3 + 0', '13.41', '152.009'],
                    'category_eleven' => ['1 + 2', '9.98', '358.960'],
                    'category_twelve' => ['2 + 1', '8.52', '1.138.617'],
                    'category_thirteen' => ['2 + 0', '4.15', '2.390.942'],
                ]
        ];
    }

    /**
     * @param $lotteryName
     * @param $drawDate
     * @return EuroMillionsDraw
     */
    private function getDrawFromDatabase($lotteryName, $drawDate)
    {
        $lottery_repo = $this->entityManager->getRepository(Namespaces::ENTITIES_NS.'Lottery');
        $lottery = $lottery_repo->findOneBy(['name' => $lotteryName]);

        $draw_repo = $this->entityManager->getRepository(Namespaces::ENTITIES_NS.'EuroMillionsDraw');
        /** @var EuroMillionsDraw $euromillions_draw */
        $euromillions_draw = $draw_repo->findOneBy(['lottery' => $lottery->getId(), 'draw_date' => $drawDate]);
        return $euromillions_draw;
    }

}