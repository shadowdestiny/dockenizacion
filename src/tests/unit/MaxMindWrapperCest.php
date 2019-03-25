<?php
/**
 * Created by PhpStorm.
 * User: wilcar
 * Date: 19/03/19
 * Time: 14:06 PM
 */

namespace EuroMillions\tests\unit;

use Phalcon\Di;
use EuroMillions\web\components\MaxMindWrapper;

class MaxMindWrapperCest
{
    protected $maxMindWrapper;

    public function _before(\UnitTester $I)
    {
        $this->maxMindWrapper = new MaxMindWrapper(Di::getDefault()->get('config')['geoip_strategy']);
    }

    /**
     * method isIpForbidden
     * when ipFromGermany
     * should returnsIsForbidden
     * @param UnitTester $I
     * @group geoip
     */
    public function test_isIpForbidden_ipFromGermany_returnsIsForbidden(\UnitTester $I)
    {
        $ip = '77.156.225.7';
        $result = $this->maxMindWrapper->isIpForbidden($ip);
        $I->assertTrue($result);
    }

    /**
     * method isIpForbidden
     * when ipFromFrance
     * should returnsIsForbidden
     * @param UnitTester $I
     * @group geoip
     */
    public function test_isIpForbidden_ipFromFrance_returnsIsForbidden(\UnitTester $I)
    {
        $ip = '87.156.225.7';
        $result = $this->maxMindWrapper->isIpForbidden($ip);
        $I->assertTrue($result);
    }

    /**
     * method isIpForbidden
     * when ipFromEEUU
     * should returnsIsAllowed
     * @param UnitTester $I
     * @group geoip
     */
    public function test_isIpForbidden_ipFromEEUU_returnsIsAllowed(\UnitTester $I)
    {
        $ip = '128.101.101.101';
        $result = $this->maxMindWrapper->isIpForbidden($ip);
        $I->assertFalse($result);
    }

    /**
     * method getCountryFromIp
     * when ipFromSpain
     * should returnsES
     * @param UnitTester $I
     * @group geoip
     */
    public function test_getCountryFromIp_ipFromSpain_returnsES(\UnitTester $I)
    {
        $ip = '213.192.202.121';
        $result = $this->maxMindWrapper->getCountryFromIp($ip);
        $I->assertEquals('ES', $result);
    }

    /**
     * method getCountryFromIp
     * when ipV6FromJapan
     * should returnsJP
     * @param UnitTester $I
     * @group geoip
     */
    public function test_getCountryFromIp_ipV6FromJapan_returnsJP(\UnitTester $I)
    {
        $ip = '2001:0000:85a3:0000:0000:8a2e:0370:7334';
        $result = $this->maxMindWrapper->getCountryFromIp($ip);
        $I->assertEquals('JP', $result);
    }
}