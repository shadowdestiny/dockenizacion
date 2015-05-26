<?php
namespace tests\unit;

use EuroMillions\services\external_apis\LotteryDotIeEuroMillionsResultsStrategy;
use tests\base\EuromillionsDotIeRelatedTest;
use tests\base\UnitTestBase;

class LotteryDotIeEuroMillionsResultsStrategyUnitTest extends UnitTestBase
{
    use EuromillionsDotIeRelatedTest;

    /**
     * method load
     * when calledWithProperResult
     * should fillPropertiesCorretly
     */
    public function test_load_calledWithProperResult_fillPropertiesCorretly()
    {
        $expected_categories = [
            "5+2" => [
                "winners" => 0,
                "prize" => 21489110
            ],
            "5+1" => [
                "winners" => 4,
                "prize" => 243342
            ],
            "5" => [
                "winners" => 6,
                "prize" => 54076
            ],
            "4+2" => [
                "winners" => 22,
                "prize" => 7374
            ],
            "4+1" => [
                "winners" => 472,
                "prize" => 301
            ],
            "4" => [
                "winners" => 966,
                "prize" => 147
            ],
            "3+2" => [
                "winners" => 1091,
                "prize" => 93
            ],
            "2+2" => [
                "winners" => 17459,
                "prize" => 27
            ],
            "3+1" => [
                "winners" => 22122,
                "prize" => 20
            ],
            "3" => [
                "winners" => 47390,
                "prize" => 16
            ],
            "1+2" => [
                "winners" => 99729,
                "prize" => 13
            ],
            "2+1" => [
                "winners" => 366744,
                "prize" => 10
            ],
            "2" => [
                "winners" => 798965,
                "prize" => 5
            ],

        ];
        $expected_regular_numbers = [
            26,30,31,35,37
        ];
        $expected_lucky_numbers = [
            8,11
        ];

        $sut = new LotteryDotIeEuroMillionsResultsStrategy();
        $this->assertEquals($sut->load($this->apiResult), [$expected_categories, $expected_regular_numbers, $expected_lucky_numbers]);

    }
}