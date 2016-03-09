<?php
namespace EuroMillions\tests\unit;

use EuroMillions\web\services\external_apis\LotteryDotIeEuroMillionsResultsStrategy;
use EuroMillions\tests\base\LotteryDotIeEuromillionsRelatedTest;
use EuroMillions\tests\base\UnitTestBase;

class LotteryDotIeEuroMillionsResultsStrategyUnitTest extends UnitTestBase
{
    use LotteryDotIeEuromillionsRelatedTest;

    /**
     * method load
     * when calledWithProperResult
     * should fillPropertiesCorretly
     */
    public function test_load_calledWithProperResult_fillPropertiesCorretly()
    {
        $sut = new LotteryDotIeEuroMillionsResultsStrategy();
        $result = $sut->load($this->apiResult);
        $this->assertEquals([$result['categories'],$result['regular_numbers'],$result['lucky_numbers'] ], [$this->expectedCategories, $this->expectedRegularNumbers, $this->expectedLuckyNumbers]);
    }
}