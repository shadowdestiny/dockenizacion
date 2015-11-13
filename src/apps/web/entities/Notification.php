<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\IEntity;

class Notification extends EntityBase implements IEntity
{

    protected $id;

    protected $description;

    protected $userNotification;


    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    /**
     * @return mixed
     */
    public function getUserNotification()
    {
        return $this->userNotification;
    }

    /**
     * @param mixed $userNotification
     */
    public function setUserNotification($userNotification)
    {
        $this->userNotification = $userNotification;
    }


}