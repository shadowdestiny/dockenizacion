<?php


namespace tests\unit\admin;

use EuroMillions\admin\vo\ActionResult;
use tests\base\PhalconDiRelatedTest;
use tests\base\UnitTestBase;

class AuthUserService extends UnitTestBase
{

    use PhalconDiRelatedTest;

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * method login
     * when calledWithValidCredentials
     * should returnActionResultTrue
     */
    public function test_login_calledWithValidCredentials_returnActionResultTrue()
    {
        list($expected, $actual) = $this->exreciseUserLogin(true);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method login
     * when calledWithInvalidCredentials
     * should returnActionResultFalse
     */
    public function test_login_calledWithInvalidCredentials_returnActionResultFalse()
    {
        list($expected, $actual) = $this->exreciseUserLogin(false);
        $this->assertEquals($expected,$actual);
    }

    private function getSut()
    {
        return $this->getDomainAdminServiceFactory()->getAuthUserService();
    }

    /**
     * @return array
     */
    private function exreciseUserLogin($expected)
    {
        $expected = new ActionResult($expected);
        $credentials = [
            'user' => 'test',
            'pass' => 'test'
        ];
        $sut = $this->getSut();
        $actual = $sut->login($credentials);
        return array($expected, $actual);
    }

}