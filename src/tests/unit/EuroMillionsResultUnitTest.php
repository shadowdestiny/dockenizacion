<?php
namespace tests\unit;

use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\web\vo\EuroMillionsRegularNumber;
use EuroMillions\web\vo\EuroMillionsLine;
use tests\base\UnitTestBase;

class EuroMillionsResultUnitTest extends UnitTestBase
{
    /**
     * method construct
     * when calledWithWrongNumberOfRegularNumbersOrLuckyNumbers
     * should throw
     * @dataProvider getWrongNumberOfNumbers
     */
    public function test_construct_calledWithWrongNumberOfRegularNumbersOrLuckyNumbers_throw($regular_numbers, $lucky_numbers)
    {
        $this->setExpectedException('\InvalidArgumentException', "An EuroMillions result should have 5 regular numbers and 2 lucky numbers");
        $this->exerciseConstruct($regular_numbers, $lucky_numbers);
    }

    public function getWrongNumberOfNumbers()
    {
        return [
            [[1,2,3,4,5,6],[1,2]],
            [[1,2,3,4,5],[1,2,3]],
        ];
    }

    /**
     * method construct
     * when calledWithRepeatedRegularOrLuckyNumber
     * should throw
     * @dataProvider getRepeatedNumbers
     */
    public function test_construct_calledWithRepeatedRegularOrLuckyNumber_throw($regular_numbers, $lucky_numbers)
    {
        $this->setExpectedException('\InvalidArgumentException', "The result numbers cannot have duplicates");
        $this->exerciseConstruct($regular_numbers, $lucky_numbers);
    }

    public function getRepeatedNumbers()
    {
        return [
            [[1,2,3,4,4],[1,2]],
            [[1,2,3,4,5],[1,1]],
            [[1,2,3,3,5],[6,7]],
            [[1,2,3,4,5],[6,6]],
        ];
    }

    /**
     * method construct
     * when calledWithWrongDataTypes
     * should throw
     * @dataProvider getNumbersWithWrongTypes
     */
    public function test_construct_calledWithWrongDataTypes_throw($regular_numbers, $lucky_numbers)
    {
        $this->setExpectedException('\InvalidArgumentException', "The numbers should be proper value objects");
        (new EuroMillionsLine($regular_numbers, $lucky_numbers));
    }

    public function getNumbersWithWrongTypes()
    {
        return [
            [[new EuroMillionsRegularNumber(1),2,3,4,5], $this->getLuckyNumbers([1,2])],
            [$this->getRegularNumbers([1,2,3,4,5]), [new EuroMillionsLuckyNumber(1),2]],
        ];
    }

    /**
     * method getRegularNumbers
     * when called
     * should returnProperValues
     */
    public function test_getRegularNumbers_called_returnProperValues()
    {
        $regular_numbers = [21, 23, 34, 46, 50];
        $expected = "21,23,34,46,50";
        $lucky_numbers = [10, 11];
        $sut = new EuroMillionsLine($this->getRegularNumbers($regular_numbers),$this->getLuckyNumbers($lucky_numbers));
        $actual = $sut->getRegularNumbers();
        $this->assertEquals($expected, $actual);
    }

    /**
     * method getLuckyNumbers
     * when called
     * should returnProperVAlues
     */
    public function test_getLuckyNumbers_called_returnProperVAlues()
    {
        $regular_numbers = [21, 23, 34, 46, 50];
        $lucky_numbers = [10, 11];
        $expected = "10,11";
        $sut = new EuroMillionsLine($this->getRegularNumbers($regular_numbers),$this->getLuckyNumbers($lucky_numbers));
        $actual = $sut->getLuckyNumbers();
        $this->assertEquals($expected, $actual);
    }

    public function getRegularNumbers(array $regular_numbers)
    {
        foreach ($regular_numbers as $number) {
            $result[] = new EuroMillionsRegularNumber($number);
        }
        return $result;
    }
    public function getLuckyNumbers(array $lucky_numbers)
    {
        foreach ($lucky_numbers as $number) {
            $result[] = new EuroMillionsLuckyNumber($number);
        }
        return $result;
    }

    /**
     * @param $regular_numbers
     * @param $lucky_numbers
     */
    private function exerciseConstruct($regular_numbers, $lucky_numbers)
    {
        $regular_numbers = $this->getRegularNumbers($regular_numbers);
        $lucky_numbers = $this->getLuckyNumbers($lucky_numbers);
        (new EuroMillionsLine($regular_numbers, $lucky_numbers));
    }
}