<?php
namespace tests\unit;

use EuroMillions\shared\config\Namespaces;
use Money\Currency;
use Prophecy\Argument;
use tests\base\UnitTestBase;


class UserPreferencesServiceUnitTest extends UnitTestBase
{
    private $userRepository_double;
    private $currencyConversionService_double;
    private $storageStrategy_double;
    private $emailService_double;
    private $paymentProviderService_double;
    private $paymentMethodRepository_double;
    private $playRepository_double;

    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'User' => $this->userRepository_double,
            Namespaces::ENTITIES_NS . 'PaymentMethod' => $this->paymentMethodRepository_double,
            Namespaces::ENTITIES_NS . 'PlayConfig' => $this->playRepository_double,

        ];
    }

    public function setUp()
    {
        $this->userRepository_double = $this->getRepositoryDouble('UserRepository');
        $this->currencyConversionService_double = $this->getServiceDouble('CurrencyConversionService');
        $this->storageStrategy_double = $this->getInterfaceWebDouble('IUsersPreferencesStorageStrategy');
        $this->emailService_double = $this->getServiceDouble('EmailService');
        $this->paymentProviderService_double = $this->getServiceDouble('PaymentProviderService');
        $this->paymentMethodRepository_double = $this->getRepositoryDouble('PaymentMethodRepository');
        $this->playRepository_double = $this->getRepositoryDouble('PlayConfigRepository');
        parent::setUp();
    }

    /**
     * method getMyCurrencyNameAndSymbol
     * when currencyIsSetInStorage
     * should returnProperValueAndSymbol
     * @dataProvider getCurrenciesAndSymbols
     */
    public function test_getMyCurrencyNameAndSymbol_currencyIsSetInStorage_returnProperValueAndSymbol($code, $name, $symbol)
    {
        $this->storageStrategy_double->getCurrency()->willReturn(new Currency($code));
        $sut = $this->getSut();
        $actual = $sut->getMyCurrencyNameAndSymbol();
        $this->assertEquals(['symbol' => $symbol, 'name' => $name], $actual);
    }

    public function getCurrenciesAndSymbols()
    {
        return [
            ['EUR', 'Euro', '€'],
            ['USD', 'US Dollar', '$'],
            ['COP', 'Colombian Peso', 'COP'],
            ['OMR', 'Omani Rial', 'OMR'],
        ];
    }

    /**
     * method getMyCurrencyNameAndSymbol
     * when currencyIsNotSet
     * should returnEuro
     */
    public function test_getMyCurrencyNameAndSymbol_currencyIsNotSet_returnEuro()
    {
        $this->storageStrategy_double->getCurrency(Argument::any())->willReturn(null);
        $sut = $this->getSut();
        $actual = $sut->getMyCurrencyNameAndSymbol();
        $this->assertEquals(['symbol' => '€', 'name' => 'Euro'], $actual);
    }

    /**
     * method getCurrency
     * when calledWithoutCurrencyOnStorage
     * should returnEuro
     */
    public function test_getCurrency_calledWithoutCurrencyOnStorage_returnEuro()
    {
        $this->storageStrategy_double->getCurrency(Argument::any())->willReturn(null);
        $expected = new Currency('EUR');
        $sut = $this->getSut();
        $actual = $sut->getCurrency();
        $this->assertEquals($expected, $actual);
    }



    /**
     * @return \EuroMillions\web\services\UserService
     */
    protected function getSut()
    {
        $sut = $this->getDomainServiceFactory()->getUserPreferencesService($this->currencyConversionService_double->reveal(),
                                                                $this->storageStrategy_double->reveal());
        return $sut;
    }
}