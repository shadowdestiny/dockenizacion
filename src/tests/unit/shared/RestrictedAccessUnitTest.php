<?php
namespace EuroMillions\tests\unit\shared;

use EuroMillions\sharecomponents\RestrictedAccess;
use EuroMillions\shared\shareDTO\RestrictedAccessConfig;
use tests\base\UnitTestBase;

class RestrictedAccessUnitTest extends UnitTestBase
{
    /**
     * method isRestricted
     * when calledWithoutAListOfAllowedIps
     * should returnFalse
     */
    public function test_isRestricted_calledWithoutAListOfAllowedIps_returnFalse()
    {
        $ra_config = new RestrictedAccessConfig();
        $sut = new RestrictedAccess();
        $actual = $sut->isRestricted($ra_config);
        $this->assertFalse($actual);
    }
}