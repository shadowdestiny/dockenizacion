<?php


namespace EuroMillions\tests\unit;


use EuroMillions\web\vo\CastilloBetId;
use EuroMillions\tests\base\UnitTestBase;

class CastilloBetIdUnitTest extends UnitTestBase
{

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * method create
     * when called
     * should returnNewCastilloBetIdWithLength20
     */
    public function test_create_called_returnNewCastilloBetIdWithLength20()
    {
        $expected = 20;
        $actual = CastilloBetId::create();
        $this->assertEquals($expected,strlen($actual->id()));
    }

    /**
     * method create
     * when called
     * should returnNewCastilloBetIdNumericData
     */
    public function test_create_called_returnNewCastilloBetIdNumericData()
    {
        $actual = CastilloBetId::create();
        $this->assertTrue(is_numeric($actual->id()));
    }

    /**
     * method create
     * when called
     * should returnNewCastilloBetIdDifferent
     */
    public function test_create_called_returnNewCastilloBetIdDifferent()
    {
        $expected = CastilloBetId::create();
        usleep(1);
        $actual = CastilloBetId::create();
        $this->assertNotEquals($expected->id(),$actual->id());
    }

}