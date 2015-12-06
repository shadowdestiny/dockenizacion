<?php


namespace tests\unit;


use EuroMillions\web\components\DateTimeUtil;
use tests\base\UnitTestBase;

class DateTimeUtilUnitTest extends UnitTestBase
{

    /**
     * method getDayOfWeek
     * when called
     * should returnDayOfWeek
     */
    public function test_getDayOfWeek_called_returnDayOfWeek()
    {
        $expected = 1;
        $sut = $this->getSut();
        $actual = $sut->getDayOfWeek(new \DateTime('2015-11-16 00:00:00'));
        $this->assertEquals($expected,$actual);
    }

    /**
     * method checkOpenTicket
     * when calledWithTimeToRetryGreatherThanTimeLimit
     * should returnFalse
     */
    public function test_checkOpenTicket_calledWithTimeToRetryGreatherThanTimeLimit_returnFalse()
    {
        $expected = false;
        $time_to_retry = '1458355313';
        $sut = $this->getSut();
        $actual = $sut->checkOpenTicket($time_to_retry);
        $this->assertEquals($expected,$actual);
    }

    private function getSut()
    {
        return new DateTimeUtil();
    }

}