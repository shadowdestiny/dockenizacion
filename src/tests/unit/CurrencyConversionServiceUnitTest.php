<?php
namespace EuroMillions\tests\unit;

use EuroMillions\web\services\CurrencyConversionService;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use EuroMillions\tests\base\UnitTestBase;

class CurrencyConversionServiceUnitTest extends UnitTestBase
{
    protected $currencyXchangeApi_double;

    public function setUp()
    {
        $this->currencyXchangeApi_double = $this->getInterfaceWebDouble('IXchangeGetter');
        parent::setUp();
    }

    /**
     * method convert
     * when called
     * should callApiAndReturnConversion
     */
    public function test_convert_called_callApiAndReturnConversion()
    {
        $rate = 1.324;
        $amount = 100;
        $from_currency_name = 'EUR';
        $to_currency_name = 'USD';
        $this->currencyXchangeApi_double->getRate($from_currency_name, $to_currency_name)->willReturn($rate);
        $sut = new CurrencyConversionService($this->currencyXchangeApi_double->reveal());
        $actual = $sut->convert(new Money($amount, new Currency($from_currency_name)), new Currency($to_currency_name));
        $expected = new Money(132, new Currency($to_currency_name));
        self::assertEquals($expected, $actual);

    }
}