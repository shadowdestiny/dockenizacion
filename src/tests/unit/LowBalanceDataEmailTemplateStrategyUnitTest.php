<?php


namespace EuroMillions\tests\unit;


use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;
use EuroMillions\web\services\email_templates_strategies\LowBalanceDataEmailTemplateStrategy;
use EuroMillions\web\vo\EuroMillionsLine;
use Money\Money;
use EuroMillions\tests\base\EuroMillionsResultRelatedTest;
use EuroMillions\tests\base\UnitTestBase;

class LowBalanceDataEmailTemplateStrategyUnitTest extends UnitTestBase
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
        list(
            $draw_day_format_one,
            $draw_day_format_two,
            $money,
            ) = $this->getDataTemplatesEmail();

        $expected = [
            'jackpot_amount' => $money,
            'draw_day_format_one' => $draw_day_format_one,
            'draw_day_format_two' => $draw_day_format_two,
        ];



        $sut = $this->getSut();
        $actual = $sut->getData(new JackpotDataEmailTemplateStrategy($this->lotteriesDataService_double->reveal()));
        $this->assertEquals($expected, $actual);

    }

    private function getSut()
    {
        return new LowBalanceDataEmailTemplateStrategy($this->lotteriesDataService_double->reveal());
    }

    /**
     * @param $result_draw
     * @return array
     */
    private function getDataTemplatesEmail()
    {
        $next_draw = new \DateTime('2016-02-02 20:00:00');
        $last_draw = new \DateTime('2016-01-29 20:00:00');
        $this->lotteriesDataService_double->getNextDateDrawByLottery('EuroMillions')->willReturn($next_draw);
        $draw_day_format_one = $next_draw->format('l');
        $draw_day_format_two = $next_draw->format('j F Y');
        $money = new Money(1000, new \Money\Currency('EUR'));
        $this->lotteriesDataService_double->getNextJackpot('EuroMillions')->willReturn($money);
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [1, 2];
        $euroMillionsLine = new EuroMillionsLine($this->getRegularNumbers($regular_numbers),
            $this->getLuckyNumbers($lucky_numbers));
        $result_draw['regular_numbers'] = $euroMillionsLine->getRegularNumbersArray();
        $result_draw['lucky_numbers'] = $euroMillionsLine->getLuckyNumbersArray();
        $this->lotteriesDataService_double->getLastResult('EuroMillions')->willReturn($result_draw);
        $this->lotteriesDataService_double->getLastDrawDate('EuroMillions')->willReturn($last_draw);
        return array($draw_day_format_one, $draw_day_format_two, $money);
    }


}