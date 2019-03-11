<?php


namespace EuroMillions\web\services\notification_mediator;


abstract class Colleague
{

    /**
     * @var NotificationMediator $mediator
     */
    protected $mediator;


    public function setMediator(IMediatorNotification $mediator)
    {
        $this->mediator= $mediator;
    }
}