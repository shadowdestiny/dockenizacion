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
        $this->assertInstanceOf('EuroMillions\web\vo\EuroMillionsDrawBreakDown',$actual);
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
        $expected = $euroMillionsDrawBreakDownData;
        $sut = $this->getSut($this->getBreakDownResult());
        $actual = $sut->getCategoryOne();
        $this->assertEquals($expected,$actual);
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
    public function test_getAwardFromCategory_passProperData_returnWinningPrize($cnt_number,$cnt_start,$expected)
    {
        $sut = $this->getSut($this->getBreakDownResult());
        $actual = $sut->getAwardFromCategory($cnt_number,$cnt_start);
        $this->assertEquals($expected, $actual);
    }

    public function getWinningsPrize()
    {
        return [
            [5,2,$this->getMoneyConvertForDataProvider('0.00')],
            [5,1,$this->getMoneyConvertForDataProvider('293.926.57')],
            [5,0,$this->getMoneyConvertForDataProvider('88.177.97')],
            [4,2,$this->getMoneyConvertForDataProvider('6.680.15')],
            [4,1,$this->getMoneyConvertForDataProvider('275.16')],
            [4,0,$this->getMoneyConvertForDataProvider('131.49')],
            [3,2,$this->getMoneyConvertForDataProvider('60.87')],
            [2,2,$this->getMoneyConvertForDataProvider('18.93')],
            [3,1,$this->getMoneyConvertForDataProvider('16.73')],
            [3,0,$this->getMoneyConvertForDataProvider('13.41')],
            [1,2,$this->getMoneyConvertForDataProvider('9.98')],
            [2,1,$this->getMoneyConvertForDataProvider('8.52')],
            [2,0,$this->getMoneyConvertForDataProvider('4.15')],
        ];
    }

    private function getMoneyConvertForDataProvider( $value )
    {
        return new Money(str_replace('.','',$value)*100, new Currency('EUR'));
    }


    private function getSut(array $breakdown)
    {
        return new EuroMillionsDrawBreakDown($breakdown);
    }

    private function getBreakDownResult()
    {
        return [

                'category_one' => ['5 + 2', new Money(str_replace('.','','0.00')*100, new Currency('EUR')), '0'],
                'category_two' => ['5 + 1', new Money(str_replace('.','','293.926.57')*100, new Currency('EUR')), '9'],
                'category_three' => ['5 + 0', new Money(str_replace('.','','88.177.97')*100, new Currency('EUR')), '10'],
                'category_four' => ['4 + 2', new Money(str_replace('.','','6.680.15')*100, new Currency('EUR')), '66'],
                'category_five' => ['4 + 1', new Money(str_replace('.','','275.16')*100, new Currency('EUR')), '1.402'],
                'category_six' => ['4 + 0', new Money(str_replace('.','','131.49')*100, new Currency('EUR')), '2.934'],
                'category_seven' => ['3 + 2', new Money(str_replace('.','','60.87')*100, new Currency('EUR')), '4.527'],
                'category_eight' => ['2 + 2', new Money(str_replace('.','','18.93')*100, new Currency('EUR')), '66.973'],
                'category_nine' => ['3 + 1', new Money(str_replace('.','','16.73')*100, new Currency('EUR')), '72.488'],
                'category_ten' => ['3 + 0', new Money(str_replace('.','','13.41')*100, new Currency('EUR')), '152.009'],
                'category_eleven' => ['1 + 2', new Money(str_replace('.','','9.98')*100, new Currency('EUR')), '358.960'],
                'category_twelve' => ['2 + 1', new Money(str_replace('.','','8.52')*100, new Currency('EUR')), '1.138.617'],
                'category_thirteen' => ['2 + 0', new Money(str_replace('.','','4.15')*100, new Currency('EUR')), '2.390.942'],

        ];
    }


}