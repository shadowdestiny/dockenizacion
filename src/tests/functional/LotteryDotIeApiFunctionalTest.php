<?php
namespace tests\functional;

use EuroMillions\services\external_apis\LotteryDotIeApi;
use tests\base\EuromillionsDotIeRelatedTest;
use tests\base\UnitTestBase;

class LotteryDotIeApiFunctionalTest extends UnitTestBase
{
    use EuromillionsDotIeRelatedTest;

    /**
     * method getResultForDate
     * when called
     * should returnProperResult
     */
    public function test_getResultForDate_called_returnProperResult()
    {
        $expected_xml = new \SimpleXMLElement($this->apiResult);
        $sut = new LotteryDotIeApi();
        $actual = $sut->getResultForDate("2015-05-19");
        $actual_xml = @new \SimpleXMLElement($actual);
        $this->assertEquals($expected_xml->DrawResult->DrawNumber, $actual_xml->DrawResult->DrawNumber);
        $this->assertEquals($expected_xml->DrawResult->Structure, $actual_xml->DrawResult->Structure);
        $this->assertEquals($expected_xml->DrawResult->Numbers, $actual_xml->DrawResult->Numbers);
    }
}