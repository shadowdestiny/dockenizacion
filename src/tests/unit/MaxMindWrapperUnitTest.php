<?php


namespace EuroMillions\tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\components\MaxMindWrapper;

class MaxMindWrapperUnitTest extends UnitTestBase
{

    /**
     * method isIpForbidden
     * when called
     * should returnTrue
     */
    public function test_isIpForbidden_called_returnTrue()
    {
        $this->markTestIncomplete('Config shippable');
        $config = \Phalcon\Di::getDefault()->get('config')['geoip_strategy'];
        $sut = new MaxMindWrapper($config);
        $ip = '77.156.225.7';
        $actual = $sut->isIpForbidden($ip);
        $this->assertTrue($actual);
    }

    /**
     * method isIpForbidden
     * when called
     * should returnFalse
     */
    public function test_isIpForbidden_called_returnFalse()
    {
        $this->markTestIncomplete('Config shippable');
        $config = \Phalcon\Di::getDefault()->get('config')['geoip_strategy'];
        $sut = new MaxMindWrapper($config);
        $ip = '87.156.225.7';
        $actual = $sut->isIpForbidden($ip);
        $this->assertFalse($actual);
    }
}