<?php
namespace tests\unit\shared;

use tests\base\UnitTestBase;

class RestrictionByIpUnitTest extends UnitTestBase 
{
    /**
     * method isRestricted
     * when thereIsNoListOfAllowedIps
     * should returnFalse
     */
    public function test_isRestricted_thereIsNoListOfAllowedIps_returnFalse()
    {
        $this->markTestIncomplete();
    }

    /**
     * method isRestricted
     * when clientIpIsNotOnAllowedIps
     * should returnTrue
     */
    public function test_isRestricted_clientIpIsNotOnAllowedIps_returnTrue()
    {
        $this->markTestIncomplete();
    }

    /**
     * method isRestricted
     * when clientIpIsOnAllowedIps
     * should returnFalse
     */
    public function test_isRestricted_clientIpIsOnAllowedIps_returnFalse()
    {
        $this->markTestIncomplete();
    }
    
}
