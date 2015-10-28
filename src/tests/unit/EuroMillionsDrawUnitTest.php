<?php
namespace tests\unit;

use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\vo\EuroMillionsLine;
use tests\base\EuroMillionsResultRelatedTest;
use tests\base\UnitTestBase;

class EuroMillionsDrawUnitTest extends UnitTestBase
{
    use EuroMillionsResultRelatedTest;
    /**
     * method createResult
     * when calledWithProperNumbers
     * should createProperObject
     */
    public function test_createResult_calledWithProperNumbers_createProperObject()
    {
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $sut = new EuroMillionsDraw();
        $sut->createResult($regular_numbers, $lucky_numbers);
        $expected = new EuroMillionsLine($this->getRegularNumbers($regular_numbers), $this->getLuckyNumbers($lucky_numbers));
        $this->assertEquals($expected, $sut->getResult());
    }
}