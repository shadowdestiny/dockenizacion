<?php


namespace EuroMillions\tests\unit;


use EuroMillions\web\services\email_templates_strategies\LatestResultsDataEmailTemplateStrategy;
use EuroMillions\web\vo\EuroMillionsLine;
use Money\Money;
use Prophecy\Argument;
use EuroMillions\tests\base\EuroMillionsResultRelatedTest;
use EuroMillions\tests\base\UnitTestBase;

class LatestResultsDataEmailTemplateStrategyUnitTest extends UnitTestBase
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
        list($last_draw,
            $money,
            $result_draw) = $this->getDataEmailTemplate();

        $expected = [
            'jackpot_amount' => $money,
            'draw_result' => $result_draw,
            'last_draw_date' => $last_draw
        ];
        $sut = $this->getSut();
        $emailTemplateDataStrategy_double = $this->getInterfaceWebDouble('IEmailTemplateDataStrategy');
        $emailTemplateDataStrategy_double->getData(Argument::type('EuroMillions\web\interfaces\IEmailTemplateDataStrategy'))->willReturn([]);
        $actual = $sut->getData(new LatestResultsDataEmailTemplateStrategy($this->lotteryService_double->reveal()));
        $this->assertEquals($expected, $actual);

    }

    private function getSut()
    {
        return new LatestResultsDataEmailTemplateStrategy($this->lotteryService_double->reveal());
    }

    /**
     * @return array
     */
    private function getDataEmailTemplate()
    {
        $next_draw = new \DateTime('2016-02-02 20:00:00');
        $last_draw = new \DateTime('2016-01-29 20:00:00');
        $money = new Money(1000, new \Money\Currency('EUR'));
        $this->lotteryService_double->getNextDateDrawByLottery('EuroMillions')->willReturn($next_draw);
        $this->lotteryService_double->getNextJackpot('EuroMillions')->willReturn($money);
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [1, 2];

        $euroMillionsLine = new EuroMillionsLine($this->getRegularNumbers($regular_numbers),

            $this->getLuckyNumbers($lucky_numbers));


        $result_draw['regular_numbers'] = $euroMillionsLine->getRegularNumbersArray();
        $result_draw['lucky_numbers'] = $euroMillionsLine->getLuckyNumbersArray();
        $this->lotteryService_double->getLastResult('EuroMillions')->willReturn($result_draw);
        $this->lotteryService_double->getLastDrawDate('EuroMillions')->willReturn($last_draw);
        return array($last_draw, $money, $result_draw);
    }


}