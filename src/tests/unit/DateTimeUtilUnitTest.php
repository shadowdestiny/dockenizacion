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


    /**
     * method getNumWeeksBetweenDates
     * when calledPassingTwoDatesValids
     * should returnNumWeeksBetweenDatesPassed
     */
    public function test_getNumWeeksBetweenDates_calledPassingTwoDatesValids_returnNumWeeksBetweenDatesPassed()
    {
        $date_ini = new \DateTime('2015-12-23');
        $date_end = new \DateTime('2016-01-06');
        $sut = $this->getSut();
        $actual = $sut->getNumWeeksBetweenDates($date_ini,$date_end);
        $this->assertEquals(2,$actual);
    }


    /**
     * method getTimeRemainingToCloseDraw
     * when called
     * should returnRemainingTimeToCloseDrawInMiliSeconds
     */
    public function test_getTimeRemainingToCloseDraw_called_returnRemainingTimeToCloseDraw()
    {
        $sut = $this->getSut();
        $time_to_close_draw = new \DateTime('2016-01-22 12:00:00');
        $time_now = new \DateTime('2016-01-22 11:31:00');
        $actual = $sut->getTimeRemainingToCloseDraw($time_to_close_draw, $time_now);
    }
    

    private function getSut()
    {
        return new DateTimeUtil();
    }

}