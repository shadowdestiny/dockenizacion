<?php


namespace EuroMillions\web\entities;

use EuroMillions\web\interfaces\IEntity;

class UserNotifications extends EntityBase implements IEntity
{
    protected $id;

    protected $user;

    protected $notification;

    protected $active;

    protected $config_value;


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
        return $this->config_value;
    }

    /**
     * @param mixed $config_value
     */
    public function setConfigValue($config_value)
    {
        $this->config_value = $config_value;
    }

}