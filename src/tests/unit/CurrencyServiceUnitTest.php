<?php
namespace tests\unit;

use EuroMillions\services\CurrencyService;
use Money\Currency;
use Money\CurrencyPair;
use Money\Money;
use tests\base\UnitTestBase;

class CurrencyServiceUnitTest extends UnitTestBase
{
    /**
     * method convert
     * when calledWithProperCurrencies
     * should returnProperMoneyObject
     */
    public function test_convert_calledWithProperCurrencies_returnProperMoneyObject()
    {
        $yahooCurrencyApi_stub = $this->prophesize('\EuroMillions\services\external_apis\YahooCurrencyApi');
        $yahooCurrencyApi_stub->getRate('EUR', 'USD')->willReturn(new CurrencyPair(new Currency('EUR'), new Currency('USD'), 1.25));

        $sut = new CurrencyService($yahooCurrencyApi_stub->reveal());
        $actual = $sut->convert(new Money(1000, new Currency('EUR')), new Currency('USD'));
        $this->assertEquals(new Money(1250, new Currency('USD')), $actual);
    }
}