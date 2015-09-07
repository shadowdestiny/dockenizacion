<?php
namespace tests\unit;

use EuroMillions\vo\ContactFormInfo;
use EuroMillions\vo\Email;
use Money\Currency;
use Prophecy\Argument;
use tests\base\UnitTestBase;

class UserServiceUnitTest extends UnitTestBase
{
    private $userRepository_double;
    private $currencyService_double;
    private $storageStrategy_double;
    private $emailService_double;

    public function setUp()
    {
        parent::setUp();
        $this->userRepository_double = $this->getRepositoryDouble('UserRepository');
        $this->currencyService_double = $this->getServiceDouble('CurrencyService');
        $this->storageStrategy_double = $this->getInterfaceDouble('IUsersPreferencesStorageStrategy');
        $this->emailService_double = $this->getServiceDouble('EmailService');
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
     * method contactRequest
     * when called
     * should returnServiceActionResultTrue
     */
    public function test_contactRequest_called_returnServiceActionResultTrue()
    {
        $sut = $this->getSut();
        $actual = $sut->contactRequest(
            new ContactFormInfo(new Email('raul.mesa@panamedia.net'),
            'Raul Mesa Ros',
            'I have a problem',
            'Playing the game'
            )
        );
        $this->assertInstanceOf('Euromillions\vo\ServiceActionResult', $actual);
        $this->assertTrue($actual->success());
    }

    /**
     * @return \EuroMillions\services\UserService
     */
    protected function getSut()
    {
        $sut = $this->getDomainServiceFactory()->getUserService($this->userRepository_double->reveal(), $this->currencyService_double->reveal(), $this->storageStrategy_double->reveal(), $this->emailService_double->reveal());
        return $sut;
    }
}