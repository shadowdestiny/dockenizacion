<?php

namespace EuroMillions\web\vo\dto;


use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;

class UserNotificationsDTO extends DTOBase implements IDto
{

    public $userNotifications;

    public $id;

    public $user;

    public $notification;

    public $active;

    public $config_value;

    public $name;

    public $type;

    public function __construct(UserNotifications $userNotifications)
    {
        $this->userNotifications = $userNotifications;
        $this->exChangeObject();
    }

    public function toArray()
    {

    }

    public function exChangeObject()
    {
        $this->user = new UserDTO($this->userNotifications->getUser());
        $this->notification = new NotificationDTO($this->userNotifications->getNotification());
        $this->active = $this->userNotifications->getActive();
        $this->config_value = $this->userNotifications->getConfigValue()->__toString();
        $this->id = $this->userNotifications->getId();
        $this->type = $this->userNotifications->getNotification()->getId();
        $this->name = $this->nameFormatted();
    }

    private function nameFormatted()
    {
        $type = $this->notification->getNotificationType();
        switch ($type) {
            case 1:
                $name = 'jackpot_reach';
                break;
            case 2:
                $name = 'autoplay_funds';
                break;
            case 3:
                $name = 'autoplay_lastdraw';
                break;
            case 4:
                $name = 'results';
                break;
            case 5:
                $name = 'email_marketing';
                break;
            case 6:
                $name = 'email_marketing';
                break;

            default:
                throw new \Exception();
        }

        return $name;
    }


}