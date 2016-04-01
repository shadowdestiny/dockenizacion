<?php


namespace EuroMillions\web\services\user_notifications_strategies;


use EuroMillions\web\entities\User;
use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\interfaces\IUserNotificationStrategy;
use EuroMillions\web\services\UserService;
use EuroMillions\web\vo\NotificationValue;
use Money\Currency;
use Money\Money;

class UserNotificationJackpotStrategy implements IUserNotificationStrategy
{

    private $userService;
    private $jackpot;

    public function __construct( UserService $userService, Money $jackpot )
    {
        $this->userService = $userService;
        $this->jackpot = $jackpot;
    }

    public function accomplishNotification(User $user)
    {
        $userNotificationResult = $this->userService->getActiveNotificationsByUserAndType($user,
                                                                NotificationValue::NOTIFICATION_THRESHOLD);
        if(!$userNotificationResult->success()) {
            return false;
        }
        /** @var UserNotifications $userNotification */
        $userNotification = $userNotificationResult->getValues();
        $value = new Money((int) $userNotification->getConfigValue()->getValue(), new Currency('EUR'));
        return $value->getAmount() < $this->jackpot->getAmount();
    }
}