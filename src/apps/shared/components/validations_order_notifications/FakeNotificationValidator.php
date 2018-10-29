<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 29/10/18
 * Time: 11:40
 */

namespace EuroMillions\shared\components\validations_order_notifications;


use EuroMillions\shared\interfaces\IValidatorOrderNotifications;

class FakeNotificationValidator implements IValidatorOrderNotifications
{

    public function validate()
    {
        return true;
    }
}