<?php
namespace tests\unit;

use EuroMillions\services\DomainServiceFactory;
use EuroMillions\services\ServiceFactory;
use Money\Currency;
use Money\CurrencyPair;
use Money\Money;
use tests\base\UnitTestBase;

class CurrencyServiceUnitTest extends UnitTestBase
{
    private $factory;
    private $yahooCurrencyApi_double;

    public function setUp()
    {
        parent::setUp();
        $this->factory = new DomainServiceFactory($this->getDi(), new ServiceFactory($this->getDi()));
        $this->yahooCurrencyApi_double = $this->getServiceDouble('external_apis\YahooCurrencyApi');
    }

    /**
     * method convert
     * when calledWithProperCurrencies
     * should returnProperMoneyObject
     */
    public function test_convert_calledWithProperCurrencies_returnProperMoneyObject()
    {
        $this->yahooCurrencyApi_double->getRate('EUR', 'USD')->willReturn(new CurrencyPair(new Currency('EUR'), new Currency('USD'), 1.25));

        $sut = $this->getSut();
        $actual = $sut->convert(new Money(1000, new Currency('EUR')), new Currency('USD'));
        $this->assertEquals(new Money(1250, new Currency('USD')), $actual);
    }

    /**
     * method convert
     * when calledWithSameCurrency
     * should returnIncomingMoneyObject
     */
    public function test_convert_calledWithSameCurrency_returnIncomingMoneyObject()
    {
        $sut = $this->getSut();
        $currency = new Currency('EUR');
        $expected = new Money(20, $currency);
        $actual = $sut->convert($expected, $currency);
        $this->assertEquals($expected,$actual);
    }

    /**
     * @return mixed
     */
    protected function getSut()
    {
        $sut = $this->factory->getServiceFactory()->getCurrencyService($this->yahooCurrencyApi_double->reveal(), $this->getServiceDouble('LanguageService')->reveal());
        return $sut;
    }


}