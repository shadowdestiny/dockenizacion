<?php


namespace EuroMillions\web\interfaces;


use EuroMillions\web\entities\User;

interface IUserNotificationStrategy
{
    public function accomplishNotification(User $user);
}