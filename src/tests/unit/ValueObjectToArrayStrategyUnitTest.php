<?php


namespace EuroMillions\tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\EuroMillionsLineMother;
use EuroMillions\web\entities\toArrayStrategies\ValueObjectToArrayStrategy;

class ValueObjectToArrayStrategyUnitTest extends UnitTestBase
{
    /**
     * method getArray
     * when called
     * should returnToArrayWithPrefix
     */
    public function test_getArray_called_returnToArrayWithPrefix()
    {
        $expected = [
            'prefix_regular_number_one'   => 1,
            'prefix_regular_number_two'   => 2,
            'prefix_regular_number_three' => 3,
            'prefix_regular_number_four'  => 4,
            'prefix_regular_number_five'  => 5,
            'prefix_lucky_number_one'     => 1,
            'prefix_lucky_number_two'     => 2
        ];
        $sut = new ValueObjectToArrayStrategy();
        $actual = $sut->getArray('prefix', EuroMillionsLineMother::anEuroMillionsLine());
        self::assertEquals($expected, $actual);
    }
}