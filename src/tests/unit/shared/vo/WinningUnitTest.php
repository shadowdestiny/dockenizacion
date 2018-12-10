<?php
namespace EuroMillions\tests\unit\shared\vo;

use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\shared\vo\Wallet;
use EuroMillions\shared\vo\Winning;
use Money\Currency;
use Money\Money;

class WinningUnitTest extends UnitTestBase
{

    /**
     * method greaterThanOrEqualThreshold
     * when called
     * should return boolean
     *
     * @dataProvider getGreaterThanOrEqualThresholdTestCasesData
     *
     * @param $integerAmount
     * @param $integerThresholdPrice
     * @param $expected
     *
     * @throws \Money\UnknownCurrencyException
     */
    public function test_upload_called_returnNewObjectWithNewUploadedAmount($integerPrice, $integerThresholdPrice, $expected)
    {

        $price = new Money($integerPrice,new Currency('EUR'));
        $thresholdPrice = new Money($integerThresholdPrice,new Currency('EUR'));
        $lotteryId = 1;

        $sut = new Winning($price, $thresholdPrice, $lotteryId);
        $actual = $sut->greaterThanOrEqualThreshold();
        $this->assertEquals($expected, $actual);

    }


    public function getGreaterThanOrEqualThresholdTestCasesData()
    {
        // amount, thresholdPrice, expected
        return [
            [100, 100, true],
            [50, 100, false],
            [100, 50, true],
        ];
    }

}