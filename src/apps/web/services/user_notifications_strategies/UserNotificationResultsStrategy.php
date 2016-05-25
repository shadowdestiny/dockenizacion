<?php


namespace EuroMillions\web\services\user_notifications_strategies;


use EuroMillions\web\entities\User;
use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\interfaces\IUserNotificationStrategy;
use EuroMillions\web\services\UserService;
use EuroMillions\web\vo\NotificationValue;

class UserNotificationResultsStrategy implements IUserNotificationStrategy
{
    private $userService;

    public function __construct( UserService $userService )
    {
        $this->userService = $userService;
    }

    public function accomplishNotification(User $user)
    {
        $userNotificationResult = $this->userService->getActiveNotificationsByUserAndType($user,
            NotificationValue::NOTIFICATION_RESULT_DRAW);

        if(!$userNotificationResult->success()) {
            return null;
        }
        /** @var UserNotifications $userNotification */
        $userNotification = $userNotificationResult->getValues();
        return $userNotification[0]->getConfigValue();
    }
}