<?php
namespace EuroMillions\tests\unit\shared;

use EuroMillions\shared\components\restrictedAccessStrategies\RestrictionByIpAndHttpAuth;
use EuroMillions\tests\base\UnitTestBase;
use Prophecy\Argument;

class RestrictionByIpAndHttpAuthUnitTest extends UnitTestBase 
{


    private $request_double;
    private $restrictedAccessConfig_double;
    private $restrictionByIp_double;
    private $restrictionByHttpAuth_double;

    public function setUp()
    {
        parent::setUp();
        $this->request_double = $this->prophesize('\Phalcon\Http\Request');
        $this->restrictedAccessConfig_double = $this->prophesize('EuroMillions\shared\dto\RestrictedAccessConfig');
        $this->restrictionByIp_double = $this->prophesize('EuroMillions\shared\components\restrictedAccessStrategies\RestrictionByIp');
        $this->restrictionByHttpAuth_double = $this->prophesize('EuroMillions\shared\components\restrictedAccessStrategies\RestrictionByHttpAuth');
    }



    /**
     * method isRestricted
     * when isNotRestrictedByIp
     * should returnFalse
     */
    public function test_isRestricted_isNotRestrictedByIp_returnFalse()
    {
        $this->restrictionByIp_double->isRestricted(Argument::any(),Argument::any())->willReturn(false);
        $sut = $this->getSut();
        $actual = $sut->isRestricted($this->request_double->reveal(),$this->restrictedAccessConfig_double->reveal());
        $this->assertFalse($actual);
    }

    /**
     * method isRestricted
     * when isRestrictedByIpButNotByAuth
     * should returnFalse
     */
    public function test_isRestricted_isRestrictedByIpButNotByAuth_returnFalse()
    {
        $this->exerciseRestricted(false);
        $sut = $this->getSut();
        $actual = $sut->isRestricted($this->request_double->reveal(),$this->restrictedAccessConfig_double->reveal());
        $this->assertFalse($actual);
    }

    /**
     * method isRestricted
     * when isRestrictedByIpAndAuth
     * should returnTrue
     */
    public function test_isRestricted_isRestrictedByIpAndAuth_returnTrue()
    {
        $this->exerciseRestricted(true);
        $sut = $this->getSut();
        $actual = $sut->isRestricted($this->request_double->reveal(),$this->restrictedAccessConfig_double->reveal());
        $this->assertTrue($actual);
    }


    private function getSut()
    {
        return new RestrictionByIpAndHttpAuth($this->restrictionByIp_double->reveal(), $this->restrictionByHttpAuth_double->reveal());
    }

    private function exerciseRestricted($expected)
    {
        $this->restrictionByIp_double->isRestricted(Argument::any(), Argument::any())->willReturn(true);
        $this->restrictionByHttpAuth_double->isRestricted(Argument::any(), Argument::any())->willReturn($expected);
    }

}
