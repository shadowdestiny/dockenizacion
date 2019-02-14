<?php


namespace EuroMillions\web\services\notification_mediator;


abstract class Colleague
{

    /**
     * @var NotificationMediatorNotification $mediator
     */
    protected $mediator;


    public function setMediator(IMediatorNotification $mediator)
    {
        $this->mediator= $mediator;
    }
}