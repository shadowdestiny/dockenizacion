<?php

namespace tests\unit;

use EuroMillions\entities\Lottery;
use tests\base\UnitTestBase;

class LotteryUnitTest extends UnitTestBase
{
    /**
     * method getNextDrawDate
     * when called
     * should returnProperResult
     * @dataProvider getConfigurationAndExpectedResult
     */
    public function test_getNextDrawDate_called_returnProperResult($frequency, $draw_time, $now, $expectedDrawDate)
    {
        $sut = new Lottery();
        $sut->initialize([
            'id' => 1,
            'name' => 'EuroMillions',
            'active' => 1,
            'frequency' => $frequency,
            'draw_time' => $draw_time
        ]);
        $actual = $sut->getNextDrawDate($now);
        $expected = new \DateTime($expectedDrawDate);
        $this->assertEquals($expected, $actual);
    }

    public function getConfigurationAndExpectedResult()
    {
        return [
            ['y1225', '10:00:00', '2015-03-02 15:00:01', '2015-12-25 10:00:00'],//december 25th each year
            ['y1225', '10:00:00', '2015-12-25 15:00:01', '2016-12-25 10:00:00'],//december 25th each year
            ['y1225', '10:00:00', '2015-12-25 09:59:01', '2015-12-25 10:00:00'],//december 25th each year
            ['m29', '09:15:00', '2016-02-01 01:01:01', '2016-02-29 09:15:00'], //29th of each month
            ['m29', '09:15:00', '2015-02-01 01:01:01', '2015-03-29 09:15:00'], //29th of each month
            ['w1000000', '09:15:00', '2015-02-01 01:01:01', '2015-02-02 09:15:00'], //each monday
            ['w0100000', '09:15:00', '2015-02-01 01:01:01', '2015-02-03 09:15:00'], //each and tuesday
            ['w1111100', '09:15:00', '2015-02-01 01:01:01', '2015-02-02 09:15:00'], //monday to friday
            ['w0000011', '09:15:00', '2015-02-01 01:01:01', '2015-02-01 09:15:00'], //saturday and friday
            ['w0100100', '09:15:00', '2015-02-01 01:01:01', '2015-02-03 09:15:00'], //tuesday and friday
            ['w0001010', '09:15:00', '2015-02-01 01:01:01', '2015-02-05 09:15:00'], //thursday and saturday
            ['w0010001', '09:15:00', '2015-02-01 10:01:01', '2015-02-04 09:15:00'], //wednesday and sunday but hour passed
            ['d', '09:15:00', '2015-02-01 10:01:01', '2015-02-02 09:15:00'], //daily, before hour
            ['d', '09:15:00', '2015-02-01 01:01:01', '2015-02-01 09:15:00'], //daily, after hour
        ];
    }
}