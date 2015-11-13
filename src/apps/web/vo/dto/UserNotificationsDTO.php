<?php

namespace EuroMillions\web\vo\dto;


use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;

class UserNotificationsDTO extends DTOBase implements IDto
{

    public $userNotifications;

    public $user;

    public $notification;

    public $active;

    public $config_value;

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
        $this->config_value = $this->userNotifications->getConfigValue();
    }
}