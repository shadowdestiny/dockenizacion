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
        $yahooCurrencyApi_stub = $this->getMockBuilder('\EuroMillions\services\external_apis\YahooCurrencyApi')->getMock();
        $yahooCurrencyApi_stub->expects($this->any())
            ->method('getRates')
            ->will($this->returnValue(new CurrencyPair(new Currency('EUR'), new Currency('USD'), 1.25)));

        $sut = new CurrencyService($yahooCurrencyApi_stub);
        $actual = $sut->convert(1000, 'EUR', 'USD');
        $this->assertEquals(new Money(1250, new Currency('USD')), $actual);
    }
}