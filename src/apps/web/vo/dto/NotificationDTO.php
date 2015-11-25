<?php


namespace EuroMillions\web\vo\dto;


use EuroMillions\web\entities\Notification;
use EuroMillions\web\exceptions\UnsupportedOperationException;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;

class NotificationDTO extends DTOBase implements IDto
{

    private $notification;

    public $id;

    public $notification_type;

    public $description;


    /**
     * NotificationDTO constructor.
     * @param $getNotification
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
        $this->exChangeObject();
    }

    public function exChangeObject()
    {
        $this->id = $this->notification->getId();
        $this->description = $this->notification->getDescription();
        $this->notification_type = $this->notification->getNotificationType();
    }

    public function toArray()
    {
        throw new UnsupportedOperationException();
    }

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

    /**
     * @return Notification
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @param Notification $notification
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;
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