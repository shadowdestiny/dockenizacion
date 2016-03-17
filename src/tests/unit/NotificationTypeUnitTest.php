<?php


namespace EuroMillions\tests\unit;


use EuroMillions\web\vo\NotificationValue;
use EuroMillions\tests\base\UnitTestBase;

class NotificationTypeUnitTest extends UnitTestBase
{

    /**
     * method __construct
     * when called
     * should createProperObject
     */
    public function test___construct_called_createProperObject()
    {
        $type = NotificationValue::NOTIFICATION_THRESHOLD;
        $expected = 1500000;
        $sut = new NotificationValue($type,$expected);
        $this->assertEquals($expected,$sut->getValue());
    }

    /**
     * method __construct
     * when called
     * should throwNewException
     */
    public function test___construct_called_throwNewException()
    {
        $this->setExpectedException('EuroMillions\web\exceptions\InvalidNotificationException');
        $type = NotificationValue::NOTIFICATION_THRESHOLD;
        $expected = 'afsafa';
        new NotificationValue($type,$expected);
    }
}