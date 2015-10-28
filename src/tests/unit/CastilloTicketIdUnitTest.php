<?php


namespace tests\unit;


use EuroMillions\web\vo\CastilloTicketId;
use tests\base\UnitTestBase;

class CastilloTicketIdUnitTest extends UnitTestBase
{

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * method create
     * when called
     * should shouldReturnAnUniqueIdentificator
     */
    public function test_create_called_shouldReturnAnUniqueIdentificator()
    {
        $expected = 10;
        $actual = CastilloTicketId::create();
        $this->assertEquals($expected,strlen($actual->id()));
    }

    /**
     * method create
     * when calledTwoTimes
     * should resultShouldDifferent
     */
    public function test_create_calledTwoTimes_resultShouldDifferent()
    {
        $expected = CastilloTicketId::create();
        $actual = CastilloTicketId::create();
        $this->assertNotEquals($expected,$actual);
    }

}