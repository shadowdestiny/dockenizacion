<?php


namespace EuroMillions\tests\unit\admin;

use EuroMillions\admin\services\AuthUserService;
use EuroMillions\shared\vo\results\ActionResult;
use Prophecy\Argument;
use EuroMillions\tests\base\PhalconDiRelatedTest;
use EuroMillions\tests\base\UnitTestBase;

class AuthUserServiceUnitTest extends UnitTestBase
{

    use PhalconDiRelatedTest;

    private $storageStrategy_double;

    public function setUp()
    {
        $this->storageStrategy_double = $this->prophesize('\Phalcon\Session\AdapterInterface');
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
        $config = new \Phalcon\Config();
        $config->user = 'admin';
        $config->pass = 'euromillions';
        list($expected, $actual) = $this->exreciseUserLogin(true,$credentials,$config);
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
        $config = new \Phalcon\Config();
        $config->user = 'admin';
        $config->pass = 'euromill';
        list($expected, $actual) = $this->exreciseUserLogin(false,$credentials,$config);
        $this->assertEquals($expected,$actual);
    }

    private function getSut()
    {
        return new AuthUserService($this->storageStrategy_double->reveal());
    }

    /**
     * @return array
     */
    private function exreciseUserLogin($expected,$credentials,$config)
    {
        $expected = new ActionResult($expected);
        $sut = $this->getSut();
        $this->storageStrategy_double->set(Argument::any(),time());
        $actual = $sut->login($credentials,$config);
        return array($expected, $actual);
    }

}