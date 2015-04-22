<?php
namespace tests\unit;

use tests\base\UnitTestBase;

class ExampleUnitTest extends UnitTestBase
{

    public function test_unit_tests_are_working()
    {
        $this->assertEquals(true ,1);
    }

    public function test_failing()
    {
        $this->fail('example failing test');
    }
}