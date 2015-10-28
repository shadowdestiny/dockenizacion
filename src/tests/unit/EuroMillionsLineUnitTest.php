<?php


namespace tests\unit;


use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\web\vo\EuroMillionsRegularNumber;
use tests\base\UnitTestBase;

class EuroMillionsLineUnitTest extends UnitTestBase
{

    /**
     * method __construct
     * when called
     * should createPropertiesInOrder
     */
    public function test___construct_called_createPropertiesInOrder()
    {
        $reg = [20,10,15,30,35];
        $regular_numbers = [];
        foreach($reg as $regular_number){
            $regular_numbers[] = new EuroMillionsRegularNumber($regular_number);
        }
        $luck = [8,1];
        $lucky_numbers = [];
        foreach($luck as $lucky_number){
            $lucky_numbers[] = new EuroMillionsLuckyNumber($lucky_number);
        }
        $sut = new EuroMillionsLine($regular_numbers,$lucky_numbers);
        $this->assertEquals([10,15,20,30,35],$sut->getRegularNumbersArray());
        $this->assertEquals([1,8],$sut->getLuckyNumbersArray());
        $this->assertEquals("10,15,20,30,35",$sut->getRegularNumbers());
        $this->assertEquals("1,8",$sut->getLuckyNumbers());

    }

}