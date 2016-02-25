<?php


namespace tests\unit;


use EuroMillions\web\services\email_templates_strategies\WinEmailAboveDataEmailTemplateStrategy;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use tests\base\UnitTestBase;

class WinEmailAboveDataEmailTemplateStrategyUnitTest extends UnitTestBase
{

    protected $currencyService_double;
    protected $amount;
    protected $user_currency;

    public function setUp()
    {
        $this->user_currency = new Currency('USD');
        $this->amount = new Money(2, new Currency('EUR'));
        $this->currencyService_double = $this->getServiceDouble('CurrencyService');
        parent::setUp();
    }

    /**
     * method getData
     * when called
     * should returnProperDataForLatestResultTemplate
     */
    public function test_getData_called_returnProperDataForLatestResultTemplate()
    {

        $user_currency = new Currency('USD');
        $expected_result = new Money(1, new Currency('USD'));
        $this->currencyService_double->convert($this->amount,$user_currency)->willReturn($expected_result);
        $this->currencyService_double->toString($expected_result, $user_currency)->willReturn('$2');
        $expected = [
            'amount_converted' => '$2',
        ];
        $sut = $this->getSut();
        $actual = $sut->getData();
        $this->assertEquals($expected, $actual);

    }

    private function getSut()
    {
        return new WinEmailAboveDataEmailTemplateStrategy($this->amount,$this->user_currency,$this->currencyService_double->reveal());
    }


}