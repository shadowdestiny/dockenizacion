<?php
namespace tests\unit\shared;

use tests\base\UnitTestBase;

class RestrictionByIpAndHttpAuthUnitTest extends UnitTestBase 
{
    /**
     * method isRestricted
     * when isNotRestrictedByIp
     * should returnFalse
     */
    public function test_isRestricted_isNotRestrictedByIp_returnFalse()
    {
        $this->markTestIncomplete();
    }

    /**
     * method isRestricted
     * when isRestrictedByIpButNotByAuth
     * should returnFalse
     */
    public function test_isRestricted_isRestrictedByIpButNotByAuth_returnFalse()
    {
        $this->markTestIncomplete();
    }

    /**
     * method isRestricted
     * when isRestrictedByIpAndAuth
     * should returnTrue
     */
    public function test_isRestricted_isRestrictedByIpAndAuth_returnTrue()
    {
        $this->markTestIncomplete();
    }
}
