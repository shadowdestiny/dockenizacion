<?php

use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\components\ViewHelper;

class ViewHelperTest extends UnitTestBase
{
    /**
     * method setCountDownFinishBet
     * when diffDatesMoreThan5Hours
     * should returnEmptyArray
     */
    public function test_setCountDownFinishBet_diffDatesMoreThan5Hours_returnEmptyArray()
    {
        $this->assertSame([],ViewHelper::setCountDownFinishBet(30, 10, 5, new DateTime('2016-10-10 18:50:00'), new DateTime('2016-10-09 20:50:00')));
    }

    /**
     * method setCountDownFinishBet
     * when diffDatesLessThan5Hours
     * should returnEmptyArray
     */
    public function test_setCountDownFinishBet_diffDatesLessThan5Hours_returnEmptyArray()
    {
        $expectedResponse = [
            'hours' => 1,
            'minutes' => 0,
            'seconds' => 0,
            'timeLeftCountDown' => 30,
            'diffTimeActualTimeAndNextDrawTime' => 3600,
            'timeAppearCountDownAgain' => 3000,
            'timeLimitAppearCountDown' => 600
        ];
        $this->assertSame($expectedResponse,ViewHelper::setCountDownFinishBet(30, 10, 5, new DateTime('2016-10-10 18:50:00'), new DateTime('2016-10-10 17:50:00')));
    }
}
