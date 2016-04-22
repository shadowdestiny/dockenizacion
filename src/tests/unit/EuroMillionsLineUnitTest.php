<?php


namespace EuroMillions\tests\unit;


use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\web\vo\EuroMillionsRegularNumber;
use EuroMillions\tests\base\UnitTestBase;

class EuroMillionsLineUnitTest extends UnitTestBase
{

    /**
     * method __construct
     * when called
     * should createPropertiesInOrder
     */
    public function test___construct_called_createPropertiesInOrder()
    {
        $sut = $this->getSut();
        $this->assertEquals([10, 15, 20, 30, 35], $sut->getRegularNumbersArray());
        $this->assertEquals([1, 8], $sut->getLuckyNumbersArray());
        $this->assertEquals("10,15,20,30,35", $sut->getRegularNumbers());
        $this->assertEquals("1,8", $sut->getLuckyNumbers());
    }

    /**
     * method toArray
     * when called
     * should returnProperStructure
     */
    public function test_toArray_called_returnProperStructure()
    {
        $expected = [
            'regular_number_one'   => 10,
            'regular_number_two'   => 15,
            'regular_number_three' => 20,
            'regular_number_four'  => 30,
            'regular_number_five'  => 35,
            'lucky_number_one'     => 1,
            'lucky_number_two'     => 8
        ];
        $sut = $this->getSut();
        $actual = $sut->toArray();
        self::assertEquals($expected, $actual);
    }

    /**
     * @return EuroMillionsLine
     */
    private function getSut()
    {
        $reg = [20, 10, 15, 30, 35];
        $regular_numbers = [];
        foreach ($reg as $regular_number) {
            $regular_numbers[] = new EuroMillionsRegularNumber($regular_number);
        }
        $luck = [8, 1];
        $lucky_numbers = [];
        foreach ($luck as $lucky_number) {
            $lucky_numbers[] = new EuroMillionsLuckyNumber($lucky_number);
        }
        $sut = new EuroMillionsLine($regular_numbers, $lucky_numbers);
        return $sut;
    }

}