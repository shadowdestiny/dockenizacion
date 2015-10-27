<?php


namespace tests\unit;


use EuroMillions\vo\CastilloCypherKey;
use tests\base\UnitTestBase;

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
        $this->setExpectedException('EuroMillions\exceptions\InvalidNativeArgumentException');
        new CastilloCypherKey('a');
    }
}