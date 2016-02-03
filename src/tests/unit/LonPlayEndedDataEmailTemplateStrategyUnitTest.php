<?php


namespace tests\unit;


use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;
use EuroMillions\web\services\email_templates_strategies\LongPlayEndedDataEmailTemplateStrategy;
use EuroMillions\web\vo\EuroMillionsLine;
use Money\Money;
use tests\base\EuroMillionsResultRelatedTest;
use tests\base\UnitTestBase;

class LonPlayEndedDataEmailTemplateStrategyUnitTest extends UnitTestBase
{
    use EuroMillionsResultRelatedTest;

    protected $lotteriesDataService_double;

    public function setUp()
    {
        $this->lotteriesDataService_double = $this->getServiceDouble('LotteriesDataService');
        parent::setUp();
    }

    /**
     * method getData
     * when called
     * should returnProperDataForLatestResultTemplate
     */
    public function test_getData_called_returnProperDataForLatestResultTemplate()
    {
        $money = $this->getDataEmailTemplate();

        $expected = [
            'jackpot_amount' => $money,
        ];

        $sut = $this->getSut();
        $actual = $sut->getData(new JackpotDataEmailTemplateStrategy($this->lotteriesDataService_double->reveal()));
        $this->assertEquals($expected, $actual);

    }

    private function getSut()
    {
        return new LongPlayEndedDataEmailTemplateStrategy($this->lotteriesDataService_double->reveal());
    }

    /**
     * @return Money
     */
    private function getDataEmailTemplate()
    {
        $next_draw = new \DateTime('2016-02-02 20:00:00');
        $last_draw = new \DateTime('2016-01-29 20:00:00');
        $money = new Money(1000, new \Money\Currency('EUR'));
        $this->lotteriesDataService_double->getNextDateDrawByLottery('EuroMillions')->willReturn($next_draw);
        $this->lotteriesDataService_double->getNextJackpot('EuroMillions')->willReturn($money);
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [1, 2];
        $euroMillionsLine = new EuroMillionsLine($this->getRegularNumbers($regular_numbers),

            $this->getLuckyNumbers($lucky_numbers));
        $result_draw['regular_numbers'] = $euroMillionsLine->getRegularNumbersArray();
        $result_draw['lucky_numbers'] = $euroMillionsLine->getLuckyNumbersArray();
        $this->lotteriesDataService_double->getLastResult('EuroMillions')->willReturn($result_draw);
        $this->lotteriesDataService_double->getLastDrawDate('EuroMillions')->willReturn($last_draw);
        return $money;
    }


}