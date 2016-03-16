<?php
namespace EuroMillions\tests\unit\shared;

use EuroMillions\shared\components\restrictedAccessStrategies\RestrictionByIp;
use EuroMillions\tests\base\UnitTestBase;

class RestrictionByIpUnitTest extends UnitTestBase 
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
     * when thereIsNoListOfAllowedIps
     * should returnFalse
     */
    public function test_isRestricted_thereIsNoListOfAllowedIps_returnFalse()
    {
        $this->restrictedAccessConfig_double->getAllowedIps()->willReturn([]);
        $sut = $this->getSut();
        $actual = $sut->isRestricted($this->request_double->reveal(),$this->restrictedAccessConfig_double->reveal());
        $this->assertFalse($actual);
    }

    /**
     * method isRestricted
     * when clientIpIsNotOnAllowedIps
     * should returnTrue
     */
    public function test_isRestricted_clientIpIsNotOnAllowedIps_returnTrue()
    {
        $this->restrictedAccessConfig_double->getAllowedIps()->willReturn(['10.0.0.1']);
        $this->request_double->getClientAddress()->willReturn('10.0.0.2');
        $sut = $this->getSut();
        $actual = $sut->isRestricted($this->request_double->reveal(),$this->restrictedAccessConfig_double->reveal());
        $this->assertTrue($actual);
    }

    /**
     * method isRestricted
     * when clientIpIsOnAllowedIps
     * should returnFalse
     */
    public function test_isRestricted_clientIpIsOnAllowedIps_returnFalse()
    {
        $this->restrictedAccessConfig_double->getAllowedIps()->willReturn(['10.0.0.1']);
        $this->request_double->getClientAddress()->willReturn('10.0.0.1');
        $sut = $this->getSut();
        $actual = $sut->isRestricted($this->request_double->reveal(),$this->restrictedAccessConfig_double->reveal());
        $this->assertFalse($actual);

    }

    private function getSut()
    {
        return new RestrictionByIp($this->response_double->reveal(), $this->exitProcessWrapper_double->reveal());
    }

}
