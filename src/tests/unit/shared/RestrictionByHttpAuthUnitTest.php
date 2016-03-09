<?php
namespace EuroMillions\tests\unit\shared;

use EuroMillions\tests\base\UnitTestBase;

class RestrictionByHttpAuthUnitTest extends UnitTestBase 
{
    /*
     * EMTD @rmrbest
     * In order to test these component you'll have to think a little bit the strategy to use, because of the exit
     * and header function calls. You cannot mock native php functions in an easy way, but you can always... (think
     * about it)
     */

    /**
     * method isRestricted
     * when calledWithoutAuthData
     * should requestAuthAndExit
     */
    public function test_isRestricted_calledWithoutAuthData_requestAuth()
    {
        $this->markTestIncomplete();
    }

    /**
     * method isRestricted
     * when calledWithWrongAuthData
     * should requestAuthAndReturnTrue
     */
    public function test_isRestricted_calledWithWrongAuthData_requestAuthAndReturnTrue()
    {
        $this->markTestIncomplete();
    }

    /**
     * method isRestricted
     * when calledWithRightAuthData
     * should returnFalse
     */
    public function test_isRestricted_calledWithRightAuthData_returnFalse()
    {
        $this->markTestIncomplete();
    }
}
