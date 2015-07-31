<?php
namespace tests\unit;

use EuroMillions\services\preferences_strategies\WebUserPreferencesStorageStrategy;
use Money\Currency;
use Phalcon\Http\Cookie;
use Prophecy\Argument;
use tests\base\UnitTestBase;

class WebUserPreferencesStorageStrategyUnitTest extends UnitTestBase
{
    private $session_double;
    private $cookieManager_double;
    public function setUp()
    {
        parent::setUp();
        $this->session_double = $this->prophesize('EuroMillions\interfaces\ISession');
        $this->cookieManager_double = $this->prophesize('EuroMillions\interfaces\ICookieManager');
    }

    /**
     * method getCurrency
     * when calledWithValueNotInSession
     * should returnDefaultValueEuro
     */
    public function test_getCurrency_calledWithValueNotInSession_returnDefaultValueEuro()
    {
        $this->session_double->has(WebUserPreferencesStorageStrategy::CURRENCY_VAR)->willReturn(false);
        $sut = $this->getSut();
        $actual = $sut->getCurrency();
        $expected = new Currency('EUR');
        $this->assertEquals($expected, $actual);
    }

    /**
     * method getCurrency
     * when calledWithValueInSession
     * should returnValueFromSession
     */
    public function test_getCurrency_calledWithValueInSession_returnValueFromSession()
    {
        $this->session_double->has(WebUserPreferencesStorageStrategy::CURRENCY_VAR)->willReturn(true);
        $this->session_double->get(WebUserPreferencesStorageStrategy::CURRENCY_VAR)->willReturn('USD');
        $sut = $this->getSut();
        $actual = $sut->getCurrency();
        $expected = new Currency('USD');
        $this->assertEquals($expected, $actual);
    }

    /**
     * method setCurrency
     * when calledWithCurrency
     * should setCurrencyNameInSession
     */
    public function test_setCurrency_calledWithCurrency_setCurrencyNameInSession()
    {
        $this->session_double->set(WebUserPreferencesStorageStrategy::CURRENCY_VAR, 'EUR')->shouldBeCalled();
        $sut = $this->getSut();
        $sut->setCurrency(new Currency('EUR'));
    }

    /**
     * @return WebUserPreferencesStorageStrategy
     */
    protected function getSut()
    {
        $sut = new WebUserPreferencesStorageStrategy($this->session_double->reveal(), $this->cookieManager_double->reveal());
        return $sut;
    }

    /**
     * @return \EuroMillions\interfaces\IUser
     */
    protected function exerciseGetCurrentUser()
    {
        $sut = $this->getSut();
        $actual = $sut->getCurrentUser();
        return $actual;
    }

    /**
     * @param $expected
     */
    protected function exerciseSetCurrentUser($expected)
    {
        $sut = $this->getSut();
        $sut->setCurrentUser($expected);
    }
}