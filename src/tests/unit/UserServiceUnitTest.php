<?php
namespace tests\unit;

use EuroMillions\entities\GuestUser;
use EuroMillions\entities\User;
use EuroMillions\interfaces\IUser;
use Money\Currency;
use Prophecy\Argument;
use tests\base\UnitTestBase;

class UserServiceUnitTest extends UnitTestBase
{
    private $userRepository_double;
    private $currencyService_double;
    private $storageStrategy_double;

    public function setUp()
    {
        parent::setUp();
        $this->userRepository_double = $this->prophesize(self::REPOSITORIES_NAMESPACE . 'UserRepository');
        $this->currencyService_double = $this->prophesize('EuroMillions\services\CurrencyService');
        $this->storageStrategy_double = $this->prophesize('EuroMillions\interfaces\IStorageStrategy');
    }

    /**
     * method getCurrentUser
     * when called
     * should returnStrategyResult
     */
    public function test_getCurrentUser_called_returnStrategyResult()
    {
        $expected = new GuestUser();
        $this->storageStrategy_double->getCurrentUser()->willReturn($expected);
        $sut = $this->getSut();
        $actual = $sut->getCurrentUser();
        $this->assertEquals($expected, $actual);
    }

    /**
     * method isLogged
     * when called
     * should returnFalseIfCurrentUserIsGuestUserAndTrueIfCurrentUserIsUser
     * @dataProvider getIUserAndExpectedLogged
     * @param IUser $user
     * @param $expected
     */
    public function test_isLogged_called_returnFalseIfCurrentUserIsGuestUserAndTrueIfCurrentUserIsUser(IUser $user, $expected)
    {
        $this->storageStrategy_double->getCurrentUser()->willReturn($user);
        $sut = $this->getSut();
        $actual = $sut->isLogged();
        $this->assertEquals($expected, $actual);
    }

    public function getIUserAndExpectedLogged()
    {
        return [
            [new GuestUser(), false],
            [new User(), true],
        ];
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
     * @return \EuroMillions\services\UserService
     */
    protected function getSut()
    {
        $sut = $this->getDomainServiceFactory()->getUserService($this->userRepository_double->reveal(), $this->currencyService_double->reveal(), $this->storageStrategy_double->reveal());
        return $sut;
    }
}