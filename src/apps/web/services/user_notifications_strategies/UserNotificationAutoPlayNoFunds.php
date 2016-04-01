<?php


namespace EuroMillions\web\services\user_notifications_strategies;


use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\IUserNotificationStrategy;
use EuroMillions\web\services\UserService;
use EuroMillions\web\vo\NotificationValue;

class UserNotificationAutoPlayNoFunds implements IUserNotificationStrategy
{

    private $userService;

    public function __construct( UserService $userService )
    {
        $this->userService = $userService;
    }

    public function accomplishNotification(User $user)
    {
        $userNotificationResult = $this->userService->getActiveNotificationsByUserAndType($user,
            NotificationValue::NOTIFICATION_NOT_ENOUGH_FUNDS);

        return $userNotificationResult->success();
    }
}