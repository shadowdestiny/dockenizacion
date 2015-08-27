<?php
namespace tests\unit;

use EuroMillions\vo\Hostname;
use tests\base\UnitTestBase;

class HostnameUnitTest extends UnitTestBase
{
    /**
     * method __construct
     * when calledWithGoodHostname
     * should setValueObjectProperly
     * @dataProvider getGoodHostnames
     */
    public function test___construct_calledWithGoodHostname_setValueObjectProperly($hostname)
    {
        $sut = new Hostname($hostname);
        $this->assertEquals($hostname, $sut->toNative());
    }

    public function getGoodHostnames()
    {
        return [
            ['localhost'],
            ['a'],
            ['0'],
            ['a.b'],
            ['google.com'],
            ['news.google.co.uk'],
            ['xn--fsqu00a.xn--0zwm56d'],
        ];
    }

    /**
     * method __construct
     * when calledWithBadHostname
     * should throw
     * @dataProvider getBadHostnames
     */
    public function test___construct_calledWithBadHostname_throw($hostname)
    {
        $this->setExpectedException('\InvalidArgumentException');
        (new Hostname($hostname));
    }

    public function getBadHostnames()
    {
        return [
            ['goo gle.com'],
            ['google..com'],
            ['google.com '],
            ['google-.com'],
            ['.google.com'],
            ['<script'],
            ['alert('],
            ['.'],
            ['..'],
            [' '],
            ['-'],
            [''],
        ];

    }
}