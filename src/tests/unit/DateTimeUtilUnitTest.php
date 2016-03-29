<?php


namespace EuroMillions\tests\unit;


use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\tests\base\UnitTestBase;

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
        $time_to_retry = 1458686313;
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
        $sut->getTimeRemainingToCloseDraw($time_to_close_draw);
    }

    /**
     * method restMinutesToCloseDraw
     * when called
     * should returnRemainMinutesRound
     * @dataProvider getMinutesFromTimeClose
     */
    public function test_restMinutesToCloseDraw_called_returnRemainMinutesRound($date_time_close, $expected)
    {
        $sut = $this->getSut();
        $now = new \DateTime('2016-01-27 10:00:00');
        $time_close = new \DateTime($date_time_close);
        $actual = $sut->restMinutesToCloseDraw($time_close,$now);
        $this->assertEquals($expected,$actual);
    }

    public function getMinutesFromTimeClose()
    {
        return array(
            array('2016-01-27 10:35:00', 5),
            array('2016-01-27 10:40:00', 10),
            array('2016-01-27 10:32:00', 2),
            array('2016-01-27 10:45:00', 15),
            array('2016-01-27 10:31:00', 1),
        );
    }

    /**
     * method getCountDownNextDraw
     * when called
     * should returnStringWithCountDownProperlyFormat
     * @dataProvider getCountDownCases
     */
    public function test_getCountDownNextDraw_called_returnStringWithCountDownProperlyFormat($next_draw_date,$expected)
    {
        $sut = $this->getSut();
        $date_next_draw = new \DateTime($next_draw_date);
        $now = new \DateTime('2016-03-01 15:00:00');
        $actual = $sut->getCountDownNextDraw($date_next_draw, $now);
        $this->assertEquals($expected, $actual);
    }

    public function getCountDownCases()
    {
        return [
            ['2016-03-01 20:00:00', '5 hours '],
            ['2016-03-02 20:00:00', '1 day and 5 hours '],
            ['2016-03-01 15:05:00', '5 minutes '],
            ['2016-03-01 15:01:00', '1 minute'],
            ['2016-03-03 20:00:00', '2 days and 5 hours '],
        ];
    }

    private function getSut()
    {
        return new DateTimeUtil();
    }

}