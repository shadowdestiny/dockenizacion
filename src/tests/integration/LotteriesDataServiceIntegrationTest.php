<?php
namespace tests\integration;

use EuroMillions\shareconfig\Namespaces;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\services\external_apis\LotteryApisFactory;
use EuroMillions\web\services\LotteriesDataService;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\EuroMillionsLine;
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

    /**
     * method test
     * when called
     * should returnSQL
     */
    public function test_test_called_returnSQL()
    {

    }


    private function getBreakDownResult()
    {
        return [
            [
                'category_one' => ['5 + 2', new Money(str_replace('.','','0.00')*100, new Currency('EUR')), '0'],
                'category_two' => ['5 + 1', new Money(str_replace('.','','293.926.57')*100, new Currency('EUR')), '9'],
                'category_three' => ['5 + 0', new Money(str_replace('.','','88.177.97')*100, new Currency('EUR')), '10'],
                'category_four' => ['4 + 2', new Money(str_replace('.','','6.680.15')*100, new Currency('EUR')), '66'],
                'category_five' => ['4 + 1', new Money(str_replace('.','','275.16')*100, new Currency('EUR')), '1.402'],
                'category_six' => ['4 + 0', new Money(str_replace('.','','131.49')*100, new Currency('EUR')), '2.934'],
                'category_seven' => ['3 + 2', new Money(str_replace('.','','60.87')*100, new Currency('EUR')), '4.527'],
                'category_eight' => ['2 + 2', new Money(str_replace('.','','18.93')*100, new Currency('EUR')), '66.973'],
                'category_nine' => ['3 + 1', new Money(str_replace('.','','16.73')*100, new Currency('EUR')), '72.488'],
                'category_ten' => ['3 + 0', new Money(str_replace('.','','13.41')*100, new Currency('EUR')), '152.009'],
                'category_eleven' => ['1 + 2', new Money(str_replace('.','','9.98')*100, new Currency('EUR')), '358.960'],
                'category_twelve' => ['2 + 1', new Money(str_replace('.','','8.52')*100, new Currency('EUR')), '1.138.617'],
                'category_thirteen' => ['2 + 0', new Money(str_replace('.','','4.15')*100, new Currency('EUR')), '2.390.942'],
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