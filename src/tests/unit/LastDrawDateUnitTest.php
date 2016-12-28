<?php


namespace EuroMillions\tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\vo\LastDrawDate;

class LastDrawDateUnitTest extends UnitTestBase
{

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * method getLastDrawDate
     * when called
     * should returnAProperLastDrawDate
     * @dataProvider getDatesForFuture
     */
    public function test_getLastDrawDate_called_returnAProperLastDrawDate($expected,$frequency)
    {
        $sut = new LastDrawDate('2016-12-30 00:00:00',$frequency);
        $actual = $sut->getLastDrawDate();
        $this->assertEquals($expected,$actual);
    }


    public function getDatesForFuture()
    {
        return [
            ['2016-12-30 00:00:00',1],
            ['2017-01-10 00:00:00',4],
            ['2017-01-24 00:00:00',8],
            ['2017-03-21 00:00:00',24],
            ['2017-06-13 00:00:00',48]
        ];
    }





}