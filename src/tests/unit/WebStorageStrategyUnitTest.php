<?php
namespace tests\unit;

use EuroMillions\entities\GuestUser;
use EuroMillions\entities\User;
use EuroMillions\services\preferences_strategies\WebStorageStrategy;
use EuroMillions\vo\UserId;
use Money\Currency;
use Prophecy\Argument;
use tests\base\UnitTestBase;

class WebStorageStrategyUnitTest extends UnitTestBase
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
        $this->session_double->has(WebStorageStrategy::CURRENCY_VAR)->willReturn(false);
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
        $this->session_double->has(WebStorageStrategy::CURRENCY_VAR)->willReturn(true);
        $this->session_double->get(WebStorageStrategy::CURRENCY_VAR)->willReturn('USD');
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
        $this->session_double->set(WebStorageStrategy::CURRENCY_VAR, 'EUR')->shouldBeCalled();
        $sut = $this->getSut();
        $sut->setCurrency(new Currency('EUR'));
    }

    /**
     * method getCurrentUser
     * when calledWithUserInSession
     * should returnUserInSession
     */
    public function test_getCurrentUser_calledWithUserInSession_returnUserInSession()
    {
        $expected = new GuestUser();
        $this->session_double->get(WebStorageStrategy::CURRENT_USER_VAR)->willReturn($expected);
        $actual = $this->exerciseGetCurrentUser();
        $this->assertEquals($expected, $actual);
    }

    /**
     * method getCurrentUser
     * when calledWithUserNotInSessionButUserIdInCookies
     * should returnUserFromCookies
     */
    public function test_getCurrentUser_calledWithUserNotInSessionButUserIdInCookies_returnUserFromCookies()
    {
        $expected_id = UserId::create();
        $this->session_double->get(WebStorageStrategy::CURRENT_USER_VAR)->willReturn(null);
        $this->cookieManager_double->get(WebStorageStrategy::CURRENT_USER_VAR)->willReturn($expected_id);
        $expected = new GuestUser();
        $expected->setId($expected_id);
        $actual = $this->exerciseGetCurrentUser();
        $this->assertEquals($expected, $actual);
    }

    /**
     * method getCurrentUser
     * when calledWithoutUserInSessionNorCookie
     * should returnNewGuestUser
     */
    public function test_getCurrentUser_calledWithoutUserInSessionNorCookie_returnNewGuestUser()
    {
        $this->session_double->get(WebStorageStrategy::CURRENT_USER_VAR)->willReturn(null);
        $this->cookieManager_double->get(WebStorageStrategy::CURRENT_USER_VAR)->willReturn(null);
        $actual = $this->exerciseGetCurrentUser();
        $this->assertInstanceOf('EuroMillions\entities\GuestUser', $actual);

    }

    /**
     * method setCurrentUser
     * when calledWithProperUser
     * should setUserInSession
     */
    public function test_setCurrentUser_calledWithProperUser_setUserInSession()
    {
        $expected = new GuestUser();
        $this->session_double->set(WebStorageStrategy::CURRENT_USER_VAR, $expected)->shouldBeCalled();
        $this->exerciseSetCurrentUser($expected);
    }

    /**
     * method setCurrentUser
     * when calledWithGuestUser
     * should setUserIdInCookie
     */
    public function test_setCurrentUser_calledWithGuestUser_setUserIdInCookie()
    {
        $user_id = UserId::create();
        $expected = new GuestUser();
        $expected->setId($user_id);
        $this->cookieManager_double->set(WebStorageStrategy::CURRENT_USER_VAR, $user_id, WebStorageStrategy::GUEST_USER_EXPIRATION)->shouldBeCalled();
        $this->exerciseSetCurrentUser($expected);
    }

    /**
     * method setCurrentUser
     * when calledWithRegisteredUser
     * should notSetUserIdInCookie
     */
    public function test_setCurrentUser_calledWithRegisteredUser_notSetUserIdInCookie()
    {
        $user = new User();
        $this->cookieManager_double->set(Argument::any(), Argument::any(), Argument::any())->shouldNotBeCalled();
        $this->exerciseSetCurrentUser($user);
    }

    /**
     * @return WebStorageStrategy
     */
    protected function getSut()
    {
        $sut = new WebStorageStrategy($this->session_double->reveal(), $this->cookieManager_double->reveal());
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