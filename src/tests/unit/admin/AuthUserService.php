<?php


namespace tests\unit\admin;

use EuroMillions\admin\vo\ActionResult;
use tests\base\PhalconDiRelatedTest;
use tests\base\UnitTestBase;

class AuthUserService extends UnitTestBase
{

    use PhalconDiRelatedTest;
    /**
     * method login
     * when calledWithValidCredentials
     * should returnActionResultTrue
     */
    public function test_login_calledWithValidCredentials_returnActionResultTrue()
    {
        $expected = new ActionResult(true);
        $sut = $this->getSut();
        $credentials = [
            'user' => 'admin',
            'pass' => 'euromillions'
        ];
        $actual = $sut->login($credentials);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method login
     * when calledWithInvalidCredentials
     * should returnActionResultFalse
     */
    public function test_login_calledWithInvalidCredentials_returnActionResultFalse()
    {
        $expected = new ActionResult(false);
        $credentials = [
            'user' => 'test',
            'pass' => 'test'
        ];
        $sut = $this->getSut();
        $actual = $sut->login($credentials);
        $this->assertEquals($expected,$actual);
    }

    private function getSut()
    {
        return $this->getDomainAdminServiceFactory()->getAuthUserService();
    }

}