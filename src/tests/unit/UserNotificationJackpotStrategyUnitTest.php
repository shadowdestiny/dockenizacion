<?php


namespace tests\unit;


use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\services\user_notifications_strategies\UserNotificationJackpotStrategy;
use EuroMillions\web\vo\NotificationValue;
use Money\Currency;
use Money\Money;

class UserNotificationJackpotStrategyUnitTest extends UnitTestBase
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
     * should returnTrue
     */
    public function test_accomplishNotification_called_returnTrue()
    {
        $jackpot = new Money(2000000,new Currency('EUR'));
        $user = $this->prepareNotification();
        $sut = new UserNotificationJackpotStrategy($this->userService_double->reveal(), $jackpot);
        $actual = $sut->accomplishNotification($user);
        $this->assertTrue($actual);
    }

    /**
     * method accomplishNotification
     * when called
     * should returnFalse
     */
    public function test_accomplishNotification_called_returnFalse()
    {
        $jackpot = new Money(1000,new Currency('EUR'));
        $user = $this->prepareNotification();
        $sut = new UserNotificationJackpotStrategy($this->userService_double->reveal(), $jackpot);
        $actual = $sut->accomplishNotification($user);
        $this->assertFalse($actual);
    }

    /**
     * @return \EuroMillions\web\entities\User
     */
    private function prepareNotification()
    {
        $user = UserMother::aJustRegisteredUser()->aUser()->build();
        $userNotification = new UserNotifications();
        $userNotification->initialize([
            'user'        => $user,
            'active'      => 1,
            'configValue' => new NotificationValue(NotificationValue::NOTIFICATION_THRESHOLD, 1500000),
        ]);
        $this->userService_double->getActiveNotificationsByUserAndType($user, NotificationValue::NOTIFICATION_THRESHOLD)
            ->willReturn(new ActionResult(true, $userNotification));
        return $user;
    }


}