<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 29/10/18
 * Time: 11:18
 */

namespace EuroMillions\shared\components\validations_order_notifications;


use EuroMillions\shared\interfaces\IValidatorOrderNotifications;
use EuroMillions\web\vo\Order;

class TicketPurchaseNotificationValidator implements IValidatorOrderNotifications
{

    private $order;

    public function __construct(Order $order)
    {
        $this->order=$order;
    }


    public function validate()
    {

    }


}