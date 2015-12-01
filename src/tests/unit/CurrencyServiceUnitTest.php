<?php
namespace tests\unit;

use EuroMillions\shareconfig\Namespaces;
use EuroMillions\web\services\DomainServiceFactory;
use EuroMillions\web\services\ServiceFactory;
use EuroMillions\web\vo\ActionResult;
use Money\Currency;
use Money\CurrencyPair;
use Money\Money;
use Prophecy\Argument;
use tests\base\UnitTestBase;

class CurrencyServiceUnitTest extends UnitTestBase
{
    private $factory;
    private $yahooCurrencyApi_double;
    private $currencyRepository_double;


    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'Currency' => $this->currencyRepository_double
        ];
    }

    public function setUp()
    {
        $this->factory = new DomainServiceFactory($this->getDi(), new ServiceFactory($this->getDi()));
        $this->yahooCurrencyApi_double = $this->getServiceDouble('external_apis\YahooCurrencyApi');
        $this->currencyRepository_double = $this->getRepositoryDouble('CurrencyRepository');
        parent::setUp();
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
     * method getActiveCurrenciesCodeAndNames
     * when called
     * should returnActionResultTrueWithProperlyCollection
     */
    public function test_getActiveCurrenciesCodeAndNames_called_returnActionResultTrueWithProperlyCollection()
    {
        $expected = new ActionResult(true,$this->getCurrencies());
        $this->currencyRepository_double->findAll()->willReturn($this->getCurrencies());
        $sut = $this->getSut();
        $actual = $sut->getActiveCurrenciesCodeAndNames();
        $this->assertEquals($expected,$actual);
    }

    /**
     * @return mixed
     */
    protected function getSut()
    {
        $sut = $this->factory->getCurrencyService($this->yahooCurrencyApi_double->reveal());
        return $sut;
    }

    private function getCurrencies()
    {
       return [
            ['symbol' => '€', 'code' => 'EUR', 'name' => 'Euro'],
            ['symbol' => '$', 'code' => 'USD', 'name' => 'US Dollar'],
            ['symbol' => 'COP', 'code' => 'COP', 'name' => 'Colombian Peso'],
            ['symbol' => '&pound;', 'code' => 'GBP', 'name' => 'Pound Sterling'],
            ['symbol' => '&#x20bd;', 'code' => 'RUR', 'name' => 'Russian Ruble'],
            ['symbol' => '&#x43;&#x48;&#x46;', 'code' => 'CHF', 'name' => 'Swiss Franc'],
            ['symbol' => '&#x41;&#x24;', 'code' => 'AUD', 'name' => 'Australian Dolar'],
            ['symbol' => 'lei', 'code' => 'RON', 'name' => 'Romanian Leu'],
            ['symbol' => '&#1083;&#1074;', 'code' => 'BGN', 'name' => 'Bulgarian Lev'],
            ['symbol' => '&#82;', 'code' => 'ZAR', 'name' => 'South African Rand'],
            ['symbol' => '&#107;&#114;', 'code' => 'SEK', 'name' => 'Swedish Krone'],
            ['symbol' => '&#107;&#114;', 'code' => 'DKK', 'name' => 'Danish Krone'],
            ['symbol' => '&#8377;', 'code' => 'INR', 'name' => 'Indian Rupee'],
            ['symbol' => '&#x20bd;', 'code' => 'BLR', 'name' => 'Belarusian Ruble'],
            ['symbol' => '&#36;', 'code' => 'CAD', 'name' => 'Canadian Dollar'],
            ['symbol' => '&#165;', 'code' => 'CNY', 'name' => 'Chinese Yuan'],
            ['symbol' => '&#165;', 'code' => 'JPY', 'name' => 'Japanese Yen'],
            ['symbol' => '&#3647;', 'code' => 'THB', 'name' => 'Thai Baht'],
        ];
    }


}