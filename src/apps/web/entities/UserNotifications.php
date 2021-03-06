<?php


namespace EuroMillions\web\entities;

use EuroMillions\web\interfaces\IEntity;
use EuroMillions\web\vo\NotificationValue;

class UserNotifications extends EntityBase implements IEntity
{
    protected $id;

    protected $user;

    protected $active;

    /** @var  NotificationValue */
    protected $type;

    protected $notification;

    /**
     * @return mixed
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @param mixed $notification
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getConfigValue()
    {
        return $this->type;
    }

    /**
     * @param mixed $config_value
     */
    public function setConfigValue($type)
    {
        $this->type = $type;
    }

}