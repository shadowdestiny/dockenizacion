<?php
namespace EuroMillions\tests\unit;

use EuroMillions\web\entities\GuestUser;
use EuroMillions\web\services\auth_strategies\WebAuthStorageStrategy;
use EuroMillions\web\vo\UserId;
use Phalcon\Http\Cookie;
use Prophecy\Argument;
use EuroMillions\tests\base\UnitTestBase;

class WebAuthStorageStrategyUnitTest extends UnitTestBase
{
    private $session_double;
    private $cookieManager_double;

    public function setUp()
    {
        parent::setUp();
        $this->session_double = $this->getInterfaceDouble('ISession');
        $this->cookieManager_double = $this->getInterfaceDouble('ICookieManager');
    }

    /**
     * method getCurrentUser
     * when calledWithUserInSession
     * should returnUserInSession
     */
    public function test_getCurrentUser_calledWithUserInSession_returnUserInSession()
    {
        $expected = UserId::create();
        $this->session_double->get(WebAuthStorageStrategy::CURRENT_USER_VAR)->willReturn($expected->id());
        $actual = $this->exerciseGetCurrentUserId();
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
        $actual = $this->exerciseGetCurrentUserId();
        $this->assertEquals($expected_id, $actual);
    }

    /**
     * method getCurrentUser
     * when calledWithoutUserInSessionNorCookie
     * should returnNewUserId
     */
    public function test_getCurrentUser_calledWithoutUserInSessionNorCookie_returnNewUserId()
    {
        $this->session_double->get(WebAuthStorageStrategy::CURRENT_USER_VAR)->willReturn(null);
        $this->cookieManager_double->get(WebAuthStorageStrategy::CURRENT_USER_VAR)->willReturn(null);
        $this->session_double->set(Argument::any(), Argument::any())->willReturn(null);
        $this->cookieManager_double->set(Argument::any(),Argument::any(),Argument::any())->willReturn(null);
        $actual = $this->exerciseGetCurrentUserId();
        $this->assertInstanceOf($this->getVOToArgument('UserId'), $actual);

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
        $this->session_double->set(WebAuthStorageStrategy::CURRENT_USER_VAR, Argument::type('string'))->shouldBeCalled();
        $this->cookieManager_double->set(WebAuthStorageStrategy::CURRENT_USER_VAR, Argument::type('string'), Argument::that($this->getGuestExpirationCallback()))->shouldBeCalled();
        $this->exerciseGetCurrentUserId();
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
     * should setUserInSessionAndSetCookie
     */
    public function test_setCurrentUser_calledWithProperUser_setUserInSession()
    {
        $user_id = UserId::create();
        $this->session_double->set(WebAuthStorageStrategy::CURRENT_USER_VAR, $user_id->id())->shouldBeCalled();
        $this->cookieManager_double->set(WebAuthStorageStrategy::CURRENT_USER_VAR, $user_id->id(), Argument::that($this->getGuestExpirationCallback()))->shouldBeCalled();
        $this->exerciseSetCurrentUser($user_id);
    }

    /**
     * method hasRemember
     * when called
     * should returnCookieManagerResult
     */
    public function test_hasRemember_called_returnCookieManagerResult()
    {
        $expected = 'ldsjlkñasdf';
        $this->cookieManager_double->has(Argument::any())->willReturn($expected);
        $sut = $this->getSut();
        $actual = $sut->hasRemember();
        $this->assertEquals($expected, $actual);
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
     * @return \EuroMillions\web\interfaces\IUser
     */
    protected function exerciseGetCurrentUserId()
    {
        $sut = $this->getSut();
        return $sut->getCurrentUserId();
    }

    /**
     * @param $expected
     */
    protected function exerciseSetCurrentUser($expected)
    {
        $sut = $this->getSut();
        $sut->setCurrentUserId($expected);
    }

    /**
     * @return \Closure
     */
    private function getGuestExpirationCallback()
    {
        return function ($arg) {
            return $arg > WebAuthStorageStrategy::GUEST_USER_EXPIRATION;
        };
    }
}