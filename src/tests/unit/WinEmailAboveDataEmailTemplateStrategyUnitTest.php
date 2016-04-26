<?php


namespace EuroMillions\tests\unit;


use EuroMillions\web\services\email_templates_strategies\WinEmailAboveDataEmailTemplateStrategy;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use EuroMillions\tests\base\UnitTestBase;

class WinEmailAboveDataEmailTemplateStrategyUnitTest extends UnitTestBase
{

    protected $currencyConversionService_double;
    protected $amount;
    protected $user_currency;

    public function setUp()
    {
        $this->user_currency = new Currency('USD');
        $this->amount = new Money(2, new Currency('EUR'));
        $this->currencyConversionService_double = $this->getServiceDouble('CurrencyConversionService');
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
        $amount = new Money(10, new Currency('EUR'));
        $expected_result = new Money(2, new Currency('USD'));
        $expected_conversion_with_symbol = '$2';
        $this->currencyConversionService_double->convert($amount,$user_currency)->willReturn($expected_result);
        $this->currencyConversionService_double->toString($expected_result, $user_currency)->willReturn($expected_conversion_with_symbol);
        $expected = [
            'amount_converted' => $expected_conversion_with_symbol,
        ];
        $sut = new WinEmailAboveDataEmailTemplateStrategy($amount, $user_currency, $this->currencyConversionService_double->reveal());
        $actual = $sut->getData();
        $this->assertEquals($expected, $actual);

    }
}