<?php


namespace EuroMillions\tests\unit;


use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\services\user_notifications_strategies\UserNotificationResultsStrategy;
use EuroMillions\web\vo\NotificationValue;

class UserNotificationLatestResultReminderUnitTest extends UnitTestBase
{

    private $userService_double;

    public function setUp()
    {
        $this->userService_double = $this->getServiceDouble('UserService');
        parent::setUp();
    }

    /**
     * method accomplishNotification
     * when called
     * should returnOne
     */
    public function test_accomplishNotification_called_returnOne()
    {
        $user = $this->prepareNotification(1);
        $sut = new UserNotificationResultsStrategy($this->userService_double->reveal());
        $actual = $sut->accomplishNotification($user);
        $this->assertEquals(1,$actual);
    }


    /**
     * method accomplishNotification
     * when called
     * should returnZero
     */
    public function test_accomplishNotification_called_returnZero()
    {
        $user = $this->prepareNotification(0);
        $sut = new UserNotificationResultsStrategy($this->userService_double->reveal());
        $actual = $sut->accomplishNotification($user);
        $this->assertEquals(0,$actual);
    }

    /**
     * method accomplishNotification
     * when called
     * should returnNull
     */
    public function test_accomplishNotification_called_returnNull()
    {
        $user = $this->prepareNotification(0,false);
        $sut = new UserNotificationResultsStrategy($this->userService_double->reveal());
        $actual = $sut->accomplishNotification($user);
        $this->assertNull($actual);
    }

    /**
     * @param $configValue
     * @param bool $returnAction
     * @return \EuroMillions\web\entities\User
     */
    private function prepareNotification($configValue, $returnAction = true)
    {
        $user = UserMother::aJustRegisteredUser()->aUser()->build();
        $userNotification = new UserNotifications();
        $userNotification->initialize([
            'user'        => $user,
            'active'      => 1,
            'configValue' => $configValue,
        ]);
        $this->userService_double->getActiveNotificationsByUserAndType($user, NotificationValue::NOTIFICATION_RESULT_DRAW)
            ->willReturn(new ActionResult($returnAction, $userNotification));
        return $user;
    }

}