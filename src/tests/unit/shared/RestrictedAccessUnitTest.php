<?php
namespace EuroMillions\tests\unit\shared;

use EuroMillions\shared\components\RestrictedAccess;
use EuroMillions\tests\base\UnitTestBase;
use Prophecy\Argument;

class RestrictedAccessUnitTest extends UnitTestBase 
{

    private $request_double;
    private $response_double;
    private $restrictedAccessConfig_double;
    private $iRestrictedAccessStrategy_double;

    public function setUp()
    {
        parent::setUp();
        $this->request_double = $this->prophesize('\Phalcon\Http\Request');
        $this->response_double = $this->prophesize('\Phalcon\Http\Response');
        $this->iRestrictedAccessStrategy_double = $this->getSharedInterfaceDouble('IRestrictedAccessStrategy');
        $this->restrictedAccessConfig_double = $this->prophesize('EuroMillions\shared\dto\RestrictedAccessConfig');
    }


    /**
     * method isRestricted
     * when called
     * should returnStrategyResult
     */
    public function test_isRestricted_called_returnStrategyResult()
    {
        $sut = new RestrictedAccess();
        $this->iRestrictedAccessStrategy_double->isRestricted(Argument::any(),Argument::any())->willReturn(true);
        $actual = $sut->isRestricted($this->iRestrictedAccessStrategy_double->reveal(), $this->request_double->reveal(),$this->restrictedAccessConfig_double->reveal());
        $this->assertTrue($actual);
    }
    
}
