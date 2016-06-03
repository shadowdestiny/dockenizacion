<?php


namespace EuroMillions\tests\old_functional;


use EuroMillions\tests\base\DatabaseIntegrationTestBase;
use EuroMillions\web\components\MaxMindWrapper;

class MaxMindWrapperFunctionalTest extends DatabaseIntegrationTestBase
{


    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    protected function getFixtures()
    {
        return [];
    }

    /**
     * method
     * when test
     * should test
     */
    public function test_test_test_test()
    {
        $filePath = '/var/www/data/geoipdatabase';
        $sut = new MaxMindWrapper($filePath);
        $actual = $sut->isIpForbidden('77.156.225.7');
        $this->assertTrue($actual);
    }
}