<?php


namespace EuroMillions\tests\unit;


use EuroMillions\web\vo\CastilloCypherKey;
use EuroMillions\tests\base\UnitTestBase;

class CastilloCypherKeyUnitTest extends UnitTestBase
{

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * method __construct
     * when called
     * should createANumberKeyBetween0And9
     */
    public function test___construct_called_createANumberKeyBetween0And9()
    {
        $sut = CastilloCypherKey::create();
        $actual = $sut->key();
        $this->assertGreaterThanOrEqual(0,$actual);
        $this->assertLessThanOrEqual(9,$actual);
    }

    /**
     * method __construct
     * when calledWithOutOfRangeValue
     * should throwInvalidArgumentException
     */
    public function test___construct_calledWithOutOfRangeValue_throwInvalidArgumentException()
    {
        $this->setExpectedException('EuroMillions\web\exceptions\InvalidNativeArgumentException');
        new CastilloCypherKey('a');
    }
}