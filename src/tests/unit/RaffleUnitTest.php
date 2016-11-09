<?php

namespace EuroMillions\tests\unit;

use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\vo\Raffle;

class RaffleUnitTest extends UnitTestBase
{


    /**
     * method __construct
     * when called
     * should returnProperObject
     * @dataProvider getRaffleCodes
     */
    public function test___construct_called_returnProperObject($value)
    {
        $actual = new Raffle($value);
        $this->assertEquals($value,$actual->getValue());
        $this->assertEquals(substr($value,0,2),$actual->getRaffleChars());
        $this->assertEquals(substr($value,3,7),$actual->getRaffleNumbers());
    }

    public function getRaffleCodes()
    {
        return [
            [
                'BNN41949',
                'AAA12123',
                'ZZZ00000'
            ]
        ];
    }

    /**
     * method __construct
     * when called
     * should returnInvalidArgumentException
     * @dataProvider getRaffleIncorrectCodes
     */
    public function test___construct_called_returnInvalidArgumentException($value)
    {
        $this->setExpectedException('\InvalidArgumentException');
        new Raffle($value);
    }

    public function getRaffleIncorrectCodes()
    {
        return [
            [
                '43241949',
                'AA21SS23',
                'AS--?00'
            ]
        ];
    }
}