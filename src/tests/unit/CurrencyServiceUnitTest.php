<?php
namespace EuroMillions\tests\unit;

use EuroMillions\shared\config\Namespaces;
use EuroMillions\web\services\CurrencyService;
use Money\Currency;
use Prophecy\Argument;
use EuroMillions\tests\base\UnitTestBase;

class CurrencyServiceUnitTest extends UnitTestBase
{
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
        $this->yahooCurrencyApi_double = $this->getServiceDouble('external_apis\YahooCurrencyApi');
        $this->currencyRepository_double = $this->getRepositoryDouble('CurrencyRepository');
        parent::setUp();
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
        return new CurrencyService($this->getEntityManagerRevealed());
    }
}