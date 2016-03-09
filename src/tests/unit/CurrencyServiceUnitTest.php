<?php
namespace EuroMillions\tests\unit;

use EuroMillions\shared\config\Namespaces;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\services\factories\ServiceFactory;
use EuroMillions\shared\vo\results\ActionResult;
use Money\Currency;
use Prophecy\Argument;
use EuroMillions\tests\base\UnitTestBase;

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
     * method getActiveCurrenciesCodeAndNames
     * when called
     * should returnActionResultTrueWithProperlyCollection
     */
    public function test_getActiveCurrenciesCodeAndNames_called_returnActionResultTrueWithProperlyCollection()
    {
        $expected = new ActionResult(true,$this->getCurrencies());
        $this->currencyRepository_double->findBy([],['name' => 'ASC'])->willReturn($this->getCurrencies());
        $sut = $this->getSut();
        $actual = $sut->getActiveCurrenciesCodeAndNames();
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getSymbolPosition
     * when calledWithIncorrectSymbol
     * should throwException
     */
    public function test_getSymbolPosition_calledWithIncorrectSymbol_throwException()
    {
        $this->setExpectedException('\Exception');
        $currency = new Currency('TEST');
        $locale = new Currency('TEST');
        $sut = $this->getSut();
        $sut->getSymbolPosition($locale,$currency);
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