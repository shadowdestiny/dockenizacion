<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 15/06/18
 * Time: 11:03
 */

namespace EuroMillions\tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\vo\PowerBallDrawBreakDown;

class PowerBallDrawBreakDownUnitTest extends UnitTestBase
{

    /**
     * method __construct
     * when calledWithProperData
     * should createsProperObject
     */
    public function test___construct_calledWithProperData_createsProperObject()
    {

        $breakDown = $this->getBreakDownResult();
        $actual = $this->getSut($breakDown);

    }



    private function getSut(array $breakdown)
    {
        return new PowerBallDrawBreakDown($breakdown);
    }

    private function getBreakDownResult()
    {
        return ['prizes' =>
                [
                    'match-0-p' => "4.00",
                    'match-0-p-pp' => "12.00",
                    'match-1-p' => "4.00",
                    'match-1-p-pp' => "12.00",
                    'match-2-p' => "7.00",
                    'match-2-p-pp' => "21.00",
                    'match-3' =>"7.00",
                    'match-3-p' =>"100.00",
                    'match-3-p-pp' =>"300.00",
                    'match-3-pp' => "21.00",
                    'match-4' => "100.00",
                    'match-4-p' => "50000.00",
                    'match-4-p-pp' => "150000.00",
                    'match-4-pp' => "300.00",
                    'match-5' => "1000000.00",
                    'match-5-p' => "337000000.00",
                    'match-5-pp' =>"2000000.00"
                ],
                'winners' =>
                [
                    'match-0-p' =>841204,
                    'match-0-p-pp' => 132316,
                    'match-1-p' =>368655,
                    'match-1-p-pp' =>59697,
                    'match-2-p' =>50061,
                    'match-2-p-pp' =>8229,
                    'match-3' =>56795,
                    'match-3-p' =>2331,
                    'match-3-p-pp' =>381,
                    'match-3-pp' =>9022,
                    'match-4' =>851,
                    'match-4-p' =>35,
                    'match-4-p-pp' =>5,
                    'match-4-pp' =>127,
                    'match-5' =>1,
                    'match-5-p' =>0,
                    'match-5-pp' =>1
                ]
        ];

    }

}