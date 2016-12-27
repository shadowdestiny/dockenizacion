<?php

namespace EuroMillions\tests\unit;

use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\vo\Discount;

class DiscountUnitTest extends UnitTestBase
{


    public function setUp()
    {
        parent::setUp();
    }


    /**
     * method __construct
     * when called
     * should shouldSetValuePropertyCorrectly
     * @dataProvider getExpectedValue
     */
    public function test__construct_called_shouldSetValuePropertyCorrectly($frequency,$expected)
    {
        $actual = new Discount($frequency, $this->getBundle());
        $this->assertEquals($expected,$actual->getValue());
    }

    private function getBundle()
    {
        return [
            ['draws' => '1', 'description' => '1 Draw', 'price_description' => 'Line', 'price' => '3', 'discount' => 0, 'checked' => 'active'],
            ['draws' => '4', 'description' => '4 Draws', 'price_description' => 'Line', 'price' => '3', 'discount' => 0, 'checked' => ''],
            ['draws' => '8', 'description' => '8 Draws', 'price_description' => 'Month', 'price' => '24', 'discount' => 0, 'checked' => ''],
            ['draws' => '24', 'description' => '24 Draws', 'price_description' => 'Month', 'price' => '24', 'discount' => 1.25, 'checked' => ''],
            ['draws' => '48', 'description' => '48 Draws', 'price_description' => 'Month', 'price' => '24', 'discount' => 4.5, 'checked' => ''],
        ];
    }

    public function getExpectedValue()
    {
        return [
            ['1',0],
            ['4', 0],
            ['8',0],
            ['24',1.25],
            ['48',4.5]
        ];
    }




}