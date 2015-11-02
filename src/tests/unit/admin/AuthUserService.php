<?php


namespace tests\unit\admin;

use EuroMillions\admin\vo\ActionResult;
use Prophecy\Argument;
use tests\base\PhalconDiRelatedTest;
use tests\base\UnitTestBase;

class AuthUserService extends UnitTestBase
{

    use PhalconDiRelatedTest;

    private $storageStrategy_double;

    public function setUp()
    {
        $this->storageStrategy_double = $this->getInterfaceDouble('ISession');
        parent::setUp();
    }

    /**
     * method login
     * when calledWithValidCredentials
     * should returnActionResultTrue
     */
    public function test_login_calledWithValidCredentials_returnActionResultTrue()
    {
        $credentials = [
            'user' => 'admin',
            'pass' => 'euromillions'
        ];
        list($expected, $actual) = $this->exreciseUserLogin(true,$credentials);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method login
     * when calledWithInvalidCredentials
     * should returnActionResultFalse
     */
    public function test_login_calledWithInvalidCredentials_returnActionResultFalse()
    {
        $credentials = [
            'user' => 'admin',
            'pass' => 'euromillions2'
        ];
        list($expected, $actual) = $this->exreciseUserLogin(false,$credentials);
        $this->assertEquals($expected,$actual);
    }

    private function getSut()
    {
        return $this->getDomainAdminServiceFactory()->getAuthUserService($this->storageStrategy_double->reveal());
    }

    /**
     * @return array
     */
    private function exreciseUserLogin($expected,$credentials)
    {
        $expected = new ActionResult($expected);
        $sut = $this->getSut();
        $this->storageStrategy_double->set(Argument::any(),time());
        $actual = $sut->login($credentials);
        return array($expected, $actual);
    }

}