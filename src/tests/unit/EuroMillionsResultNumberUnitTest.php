<?php
namespace tests\unit;

use EuroMillions\web\vo\EuroMillionsRegularNumber;
use tests\base\UnitTestBase;

class EuroMillionsResultNumberUnitTest extends UnitTestBase
{
    const ERROR_MSG = 'This result number should be an integer between 1 and 50';

    /**
     * method setNumber
     * when calledWithNotAnInt
     * should throwProperErrorMessage
     */
    public function test_setNumber_calledWithNotAnInt_throwProperErrorMessage()
    {
        $this->setExpectedException('\InvalidArgumentException', self::ERROR_MSG);
        (new EuroMillionsRegularNumber('02'));
    }

    /**
     * method setNumber
     * when calledWithANumberOutOfBounds
     * should throwProperErrorMessage
     * @dataProvider getOutOfBoundsNumbers
     * @param $number
     */
    public function test_setNumber_calledWithANumberOutOfBounds_throwProperErrorMessage($number)
    {
        $this->setExpectedException('\OutOfBoundsException', self::ERROR_MSG);
        (new EuroMillionsRegularNumber($number));
    }

    public function getOutOfBoundsNumbers()
    {
        return [
            [0],
            [-1],
            [-40],
            [51],
            [848]
        ];
    }

    /**
     * method setNumber
     * when calledWithALegalNumber
     * should setTheProperNumber
     * @dataProvider getProperNumbers
     * @param $number
     */
    public function test_setNumber_calledWithALegalNumber_setTheProperNumber($number)
    {
        $result_number = new EuroMillionsRegularNumber($number);
        $this->assertSame($number, $result_number->getNumber());
    }

    public function getProperNumbers()
    {
        for($i=1; $i<=50; $i++) {
            $result[] = [$i];
        }
        return $result;
    }
}