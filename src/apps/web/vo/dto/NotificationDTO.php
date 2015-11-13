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
    }

    public function toArray()
    {
        throw new UnsupportedOperationException();
    }
}