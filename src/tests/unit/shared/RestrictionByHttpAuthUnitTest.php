<?php
namespace EuroMillions\tests\unit\shared;

use EuroMillions\shared\components\restrictedAccessStrategies\RestrictionByHttpAuth;
use EuroMillions\shared\vo\HttpUser;
use EuroMillions\tests\base\UnitTestBase;
use Phalcon\Http\Request;
use Prophecy\Argument;

class RestrictionByHttpAuthUnitTest extends UnitTestBase 
{

    private $request_double;
    private $restrictedAccessConfig_double;
    private $response_double;
    private $exitProcessWrapper_double;


    public function setUp()
    {
        parent::setUp();
        $this->request_double = $this->prophesize('\Phalcon\Http\Request');
        $this->response_double = $this->prophesize('\Phalcon\Http\Response');
        $this->restrictedAccessConfig_double = $this->prophesize('EuroMillions\shared\dto\RestrictedAccessConfig');
        $this->exitProcessWrapper_double = $this->prophesize('EuroMillions\web\components\ExitProcessWrapper');
    }

    /**
     * method isRestricted
     * when calledWithoutAuthData
     * should requestAuthAndExit
     */
    public function test_isRestricted_calledWithoutAuthData_requestAuth()
    {
        $this->request_double->getBasicAuth()->willReturn(false);
        $this->response_double->setRawHeader(Argument::any())->shouldBeCalledTimes(2);
        $this->response_double->send()->shouldBeCalled();
        $this->exitProcessWrapper_double->finish()->shouldBeCalled();
        $sut = $this->getSut();
        $sut->isRestricted($this->request_double->reveal(), $this->restrictedAccessConfig_double->reveal());
    }

    /**
     * method isRestricted
     * when calledWithWrongAuthData
     * should requestAuthAndReturnTrue
     */
    public function test_isRestricted_calledWithWrongAuthData_requestAuthAndReturnTrue()
    {
        $http_user = new HttpUser('test','test01');
        $this->request_double->getBasicAuth()->willReturn(['username' => 'test', 'password' => 'test02']);
        $this->restrictedAccessConfig_double->getAllowedHttpUser()->willReturn($http_user);
        $this->response_double->setRawHeader(Argument::any())->shouldBeCalledTimes(2);
        $sut = $this->getSut();
        $actual = $sut->isRestricted($this->request_double->reveal(), $this->restrictedAccessConfig_double->reveal());
        $this->assertTrue($actual);
    }

    /**
     * method isRestricted
     * when calledWithRightAuthData
     * should returnFalse
     */
    public function test_isRestricted_calledWithRightAuthData_returnFalse()
    {
        $http_user = new HttpUser('test','test01');
        $this->request_double->getBasicAuth()->willReturn(['username' => 'test', 'password' => 'test01']);
        $this->restrictedAccessConfig_double->getAllowedHttpUser()->willReturn($http_user);
        $sut = $this->getSut();
        $actual = $sut->isRestricted($this->request_double->reveal(), $this->restrictedAccessConfig_double->reveal());
        $this->assertFalse($actual);
    }

    private function getSut()
    {
        return new RestrictionByHttpAuth($this->response_double->reveal(), $this->exitProcessWrapper_double->reveal());
    }
}
