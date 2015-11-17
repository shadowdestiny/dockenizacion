<?php


namespace tests\unit;


use EuroMillions\web\vo\NotificationType;
use tests\base\UnitTestBase;

class NotificationTypeUnitTest extends UnitTestBase
{

    /**
     * method __construct
     * when called
     * should createProperObject
     */
    public function test___construct_called_createProperObject()
    {
        $type = NotificationType::NOTIFICATION_THRESHOLD;
        $expected = 1500000;
        $sut = new NotificationType($type,$expected);
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
        $type = NotificationType::NOTIFICATION_THRESHOLD;
        $expected = 'afsafa';
        $sut = new NotificationType($type,$expected);
    }
}