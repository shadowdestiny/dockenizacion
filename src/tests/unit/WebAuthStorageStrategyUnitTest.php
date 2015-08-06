<?php
namespace tests\unit;

use EuroMillions\entities\GuestUser;
use EuroMillions\entities\User;
use EuroMillions\services\auth_strategies\WebAuthStorageStrategy;
use EuroMillions\vo\UserId;
use Phalcon\Http\Cookie;
use Prophecy\Argument;
use tests\base\UnitTestBase;

class WebAuthStorageStrategyUnitTest extends UnitTestBase
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
     * method getCurrentUser
     * when calledWithUserInSession
     * should returnUserInSession
     */
    public function test_getCurrentUser_calledWithUserInSession_returnUserInSession()
    {
        $expected = new GuestUser();
        $this->session_double->get(WebAuthStorageStrategy::CURRENT_USER_VAR)->willReturn($expected);
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
        $cookie = new Cookie(WebAuthStorageStrategy::CURRENT_USER_VAR);
        $cookie->setValue($expected_id);
        $this->session_double->get(WebAuthStorageStrategy::CURRENT_USER_VAR)->willReturn(null);
        $this->cookieManager_double->get(WebAuthStorageStrategy::CURRENT_USER_VAR)->willReturn($cookie);
        $this->session_double->set(Argument::any(), Argument::any())->willReturn(null);
        $this->cookieManager_double->set(Argument::any(),Argument::any(),Argument::any())->willReturn(null);
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
        $this->session_double->get(WebAuthStorageStrategy::CURRENT_USER_VAR)->willReturn(null);
        $this->cookieManager_double->get(WebAuthStorageStrategy::CURRENT_USER_VAR)->willReturn(null);
        $this->session_double->set(Argument::any(), Argument::any())->willReturn(null);
        $this->cookieManager_double->set(Argument::any(),Argument::any(),Argument::any())->willReturn(null);
        $actual = $this->exerciseGetCurrentUser();
        $this->assertInstanceOf('EuroMillions\entities\GuestUser', $actual);

    }

    /**
     * method getCurrentUser
     * when calledWithoutUserInSessionNorCookie
     * should setUserInSessionAndUserIdInCookie
     */
    public function test_getCurrentUser_calledWithoutUserInSessionNorCookie_setUserInSessionAndUserIdInCookie()
    {
        $this->session_double->get(WebAuthStorageStrategy::CURRENT_USER_VAR)->willReturn(null);
        $this->cookieManager_double->get(WebAuthStorageStrategy::CURRENT_USER_VAR)->willReturn(null);
        $this->session_double->set(WebAuthStorageStrategy::CURRENT_USER_VAR, Argument::type('EuroMillions\entities\GuestUser'))->shouldBeCalled();
        $this->cookieManager_double->set(WebAuthStorageStrategy::CURRENT_USER_VAR, Argument::type('string'), WebAuthStorageStrategy::GUEST_USER_EXPIRATION)->shouldBeCalled();
        $this->exerciseGetCurrentUser();
    }

    /**
     * method getCurrentUser
     * when calledWithoutUserInSessionButUserIdInCookie
     * should setUserInSession
     */
    public function test_getCurrentUser_calledWithoutUserInSessionButUserIdInCookie_setUserInSession()
    {
        $user_id = UserId::create();
        $this->session_double->get(WebAuthStorageStrategy::CURRENT_USER_VAR)->willReturn(null);
        $this->cookieManager_double->get(WebAuthStorageStrategy::CURRENT_USER_VAR)->willReturn($user_id);
        $expected = new GuestUser();
        $expected->setId($user_id);
        $this->session_double->set(WebAuthStorageStrategy::CURRENT_USER_VAR, $expected);
    }

    /**
     * method setCurrentUser
     * when calledWithProperUser
     * should setUserInSession
     */
    public function test_setCurrentUser_calledWithProperUser_setUserInSession()
    {
        $user = new GuestUser();
        $user->initialize(['id' => UserId::create()]);
        $this->session_double->set(WebAuthStorageStrategy::CURRENT_USER_VAR, $user)->shouldBeCalled();
        $this->exerciseSetCurrentUser($user);
    }

    /**
     * method setCurrentUser
     * when calledWithGuestUser
     * should setUserIdInCookie
     */
    public function test_setCurrentUser_calledWithGuestUser_setUserIdInCookie()
    {
        $user_id = UserId::create();
        $expected = $user_id->id();
        $user = new GuestUser();
        $user->setId($user_id);
        $this->cookieManager_double->set(WebAuthStorageStrategy::CURRENT_USER_VAR, $expected, WebAuthStorageStrategy::GUEST_USER_EXPIRATION)->shouldBeCalled();
        $this->exerciseSetCurrentUser($user);
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
     * @return WebAuthStorageStrategy
     */
    protected function getSut()
    {
        $sut = new WebAuthStorageStrategy($this->session_double->reveal(), $this->cookieManager_double->reveal());
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