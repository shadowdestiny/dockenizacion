<?php


namespace tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\entities\PlayConfig;

class PlayConfigUnitTest extends UnitTestBase
{

    /**
     * method numBets
     * when called
     * should return1
     */
    public function test_numBets_called_return1()
    {
        $sut = new PlayConfig();
        $actual = $sut->numBets();
        $this->assertEquals(1,$actual);
    }


}