<?php


namespace EuroMillions\tests\unit;


use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;
use EuroMillions\web\services\email_templates_strategies\LowBalanceDataEmailTemplateStrategy;
use EuroMillions\web\vo\EuroMillionsLine;
use Money\Currency;
use Money\Money;
use EuroMillions\tests\base\EuroMillionsResultRelatedTest;
use EuroMillions\tests\base\UnitTestBase;

class LowBalanceDataEmailTemplateStrategyUnitTest extends UnitTestBase
{
    use EuroMillionsResultRelatedTest;

    protected $lotteryService_double;

    public function setUp()
    {
        $this->lotteryService_double = $this->getServiceDouble('LotteryService');
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
        $actual = $sut->getData(new JackpotDataEmailTemplateStrategy($this->lotteryService_double->reveal()));
        $this->assertEquals($expected, $actual);

    }

    private function getSut()
    {
        return new LowBalanceDataEmailTemplateStrategy();
    }

    /**
     * @param $result_draw
     * @return array
     */
    private function getDataTemplatesEmail()
    {
        $next_draw = new \DateTime('2016-02-02 20:00:00');
        $last_draw = new \DateTime('2016-01-29 20:00:00');
        $this->lotteryService_double->getNextDateDrawByLottery('EuroMillions')->willReturn($next_draw);
        $draw_day_format_one = $next_draw->format('l');
        $draw_day_format_two = $next_draw->format('j F Y');
        $amount = new Money((int) 1000, new Currency('EUR'));
        $money = '€10';
        $this->lotteryService_double->getNextJackpot('EuroMillions')->willReturn($amount);
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [1, 2];
        $euroMillionsLine = new EuroMillionsLine($this->getRegularNumbers($regular_numbers),
            $this->getLuckyNumbers($lucky_numbers));
        $result_draw['regular_numbers'] = $euroMillionsLine->getRegularNumbersArray();
        $result_draw['lucky_numbers'] = $euroMillionsLine->getLuckyNumbersArray();
        $this->lotteryService_double->getLastResult('EuroMillions')->willReturn($result_draw);
        $this->lotteryService_double->getLastDrawDate('EuroMillions')->willReturn($last_draw);
        return array($draw_day_format_one, $draw_day_format_two, $money);
    }


}