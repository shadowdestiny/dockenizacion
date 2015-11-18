<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\IEntity;

class Notification extends EntityBase implements IEntity
{

    protected $id;

    protected $description;

    protected $notification_type;

    /**
     * @return mixed
     */
    public function getNotificationType()
    {
        return $this->notification_type;
    }

    /**
     * @param mixed $notification_type
     */
    public function setNotificationType($notification_type)
    {
        $this->notification_type = $notification_type;
    }

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

}