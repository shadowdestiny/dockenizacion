<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 25/10/18
 * Time: 12:44
 */

namespace EuroMillions\shared\components\order_actions;


use EuroMillions\shared\interfaces\IOrderAction;
use EuroMillions\web\vo\Order;

final class DepositOrderAction implements IOrderAction
{


    protected $status;

    /** @var Order $order */
    protected $order;

    /** @var \Phalcon\Events\Manager $eventsManager */
    protected $eventsManager;

    public function __construct($status, Order $order, \Phalcon\Events\Manager $eventsManager)
    {

    }

    public function execute()
    {

    }
}