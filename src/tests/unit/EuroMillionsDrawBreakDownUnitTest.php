<?php

namespace EuroMillions\tests\unit;

use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\EuroMillionsDrawBreakDownData;
use Money\Currency;
use Money\Money;

class EuroMillionsDrawBreakDownUnitTest extends UnitTestBase
{
    /**
     * method __construct
     * when calledWithProperData
     * should createsProperObject
     */
    public function test___construct_calledWithProperData_createsProperObject()
    {
        $actual = $this->getSut($this->getBreakDownResult());
        $this->assertInstanceOf('EuroMillions\web\vo\EuroMillionsDrawBreakDown', $actual);
    }


    /**
     * method getCategoryOne
     * when called
     * should returnProperEuroMillionsDrawBreakDownData
     */
    public function test_getCategoryOne_called_returnProperEuroMillionsDrawBreakDownData()
    {
        $euroMillionsDrawBreakDownData = new EuroMillionsDrawBreakDownData();
        $euroMillionsDrawBreakDownData->setLotteryPrize(new Money(0, new Currency('EUR')));
        $euroMillionsDrawBreakDownData->setWinners('0');
        $euroMillionsDrawBreakDownData->setName('5 + 2');
        $euroMillionsDrawBreakDownData->setCategoryName('category_one');
        $expected = $euroMillionsDrawBreakDownData;
        $sut = $this->getSut($this->getBreakDownResult());
        $actual = $sut->getCategoryOne();
        $this->assertEquals($expected, $actual);
    }

    /**
     * method __construct
     * when passBreakDownArrayWithCountLessThanThirteen
     * should throwLengthException
     */
    public function test___construct_passBreakDownArrayWithCountLessThanThirteen_throwLengthException()
    {
        $this->setExpectedException('\LengthException', 'Incorrect categories length from collection');
        $result = $this->getBreakDownResult();
        unset($result['category_one']);
        $sut = $this->getSut([0 => $result]);
    }

    /**
     * method getAwardFromCategory
     * when passProperData
     * should returnWinningPrize
     * @dataProvider getWinningsPrize
     */
    public function test_getAwardFromCategory_passProperData_returnWinningPrize($cnt_number, $cnt_start, $expected)
    {
        $sut = $this->getSut($this->getBreakDownResult());
        $actual = $sut->getAwardFromCategory($cnt_number, $cnt_start);
        $this->assertEquals($expected, $actual);
    }

    public function getWinningsPrize()
    {
        return [
            [5, 2, $this->getMoneyConvertForDataProvider('0.00')],
            [5, 1, $this->getMoneyConvertForDataProvider('293.926.57')],
            [5, 0, $this->getMoneyConvertForDataProvider('88.177.97')],
            [4, 2, $this->getMoneyConvertForDataProvider('6.680.15')],
            [4, 1, $this->getMoneyConvertForDataProvider('275.16')],
            [3, 2, $this->getMoneyConvertForDataProvider('131.49')],
            [4, 0, $this->getMoneyConvertForDataProvider('60.87')],
            [2, 2, $this->getMoneyConvertForDataProvider('18.93')],
            [3, 1, $this->getMoneyConvertForDataProvider('16.73')],
            [3, 0, $this->getMoneyConvertForDataProvider('13.41')],
            [1, 2, $this->getMoneyConvertForDataProvider('9.98')],
            [2, 1, $this->getMoneyConvertForDataProvider('8.52')],
            [2, 0, $this->getMoneyConvertForDataProvider('4.15')],
        ];
    }

    private function getMoneyConvertForDataProvider($value)
    {
        return new Money(str_replace('.', '', $value) * 100, new Currency('EUR'));
    }


    private function getSut(array $breakdown)
    {
        return new EuroMillionsDrawBreakDown($breakdown);
    }

    private function getBreakDownResult()
    {
        return [
            'category_one' => ['5 + 2', new Money(str_replace('.', '', '0.00') * 100, new Currency('EUR')), '0'],
            'category_two' => ['5 + 1', new Money(str_replace('.', '', '293.926.57') * 100, new Currency('EUR')), '9'],
            'category_three' => ['5 + 0', new Money(str_replace('.', '', '88.177.97') * 100, new Currency('EUR')), '10'],
            'category_four' => ['4 + 2', new Money(str_replace('.', '', '6.680.15') * 100, new Currency('EUR')), '66'],
            'category_five' => ['4 + 1', new Money(str_replace('.', '', '275.16') * 100, new Currency('EUR')), '1.402'],
            'category_six' => ['3 + 2', new Money(str_replace('.', '', '131.49') * 100, new Currency('EUR')), '2.934'],
            'category_seven' => ['4 + 0', new Money(str_replace('.', '', '60.87') * 100, new Currency('EUR')), '4.527'],
            'category_eight' => ['2 + 2', new Money(str_replace('.', '', '18.93') * 100, new Currency('EUR')), '66.973'],
            'category_nine' => ['3 + 1', new Money(str_replace('.', '', '16.73') * 100, new Currency('EUR')), '72.488'],
            'category_ten' => ['3 + 0', new Money(str_replace('.', '', '13.41') * 100, new Currency('EUR')), '152.009'],
            'category_eleven' => ['1 + 2', new Money(str_replace('.', '', '9.98') * 100, new Currency('EUR')), '358.960'],
            'category_twelve' => ['2 + 1', new Money(str_replace('.', '', '8.52') * 100, new Currency('EUR')), '1.138.617'],
            'category_thirteen' => ['2 + 0', new Money(str_replace('.', '', '4.15') * 100, new Currency('EUR')), '2.390.942'],
        ];
    }

    /**
     * method toArray
     * when called
     * should returnProperArray
     */
    public function test_toArray_called_returnProperArray()
    {
        $expected = [
            'category_one_name' => '5 + 2',
            'category_one_winners' => '0',
            'category_one_lottery_prize_amount' => 0,
            'category_one_lottery_prize_currency_name' => 'EUR',
            'category_two_name' => '5 + 1',
            'category_two_winners' => '9',
            'category_two_lottery_prize_amount' => 2939265700,
            'category_two_lottery_prize_currency_name' => 'EUR',
            'category_three_name' => '5 + 0',
            'category_three_winners' => '10',
            'category_three_lottery_prize_amount' => 881779700,
            'category_three_lottery_prize_currency_name' => 'EUR',
            'category_four_name' => '4 + 2',
            'category_four_winners' => '66',
            'category_four_lottery_prize_amount' => 66801500,
            'category_four_lottery_prize_currency_name' => 'EUR',
            'category_five_name' => '4 + 1',
            'category_five_winners' => '1.402',
            'category_five_lottery_prize_amount' => 2751600,
            'category_five_lottery_prize_currency_name' => 'EUR',
            'category_six_name' => '3 + 2',
            'category_six_winners' => '2.934',
            'category_six_lottery_prize_amount' => 1314900,
            'category_six_lottery_prize_currency_name' => 'EUR',
            'category_seven_name' => '4 + 0',
            'category_seven_winners' => '4.527',
            'category_seven_lottery_prize_amount' => 608700,
            'category_seven_lottery_prize_currency_name' => 'EUR',
            'category_eight_name' => '2 + 2',
            'category_eight_winners' => '66.973',
            'category_eight_lottery_prize_amount' => 189300,
            'category_eight_lottery_prize_currency_name' => 'EUR',
            'category_nine_name' => '3 + 1',
            'category_nine_winners' => '72.488',
            'category_nine_lottery_prize_amount' => 167300,
            'category_nine_lottery_prize_currency_name' => 'EUR',
            'category_ten_name' => '3 + 0',
            'category_ten_winners' => '152.009',
            'category_ten_lottery_prize_amount' => 134100,
            'category_ten_lottery_prize_currency_name' => 'EUR',
            'category_eleven_name' => '1 + 2',
            'category_eleven_winners' => '358.960',
            'category_eleven_lottery_prize_amount' => 99800,
            'category_eleven_lottery_prize_currency_name' => 'EUR',
            'category_twelve_name' => '2 + 1',
            'category_twelve_winners' => '1.138.617',
            'category_twelve_lottery_prize_amount' => 85200,
            'category_twelve_lottery_prize_currency_name' => 'EUR',
            'category_thirteen_name' => '2 + 0',
            'category_thirteen_winners' => '2.390.942',
            'category_thirteen_lottery_prize_amount' => 41500,
            'category_thirteen_lottery_prize_currency_name' => 'EUR',
        ];
        $sut = $this->getSut($this->getBreakDownResult());
        $actual = $sut->toArray();

        self::assertEquals($expected, $actual);
    }


}