<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 25/10/18
 * Time: 12:25
 */

namespace EuroMillions\shared\components;


use EuroMillions\shared\components\order_actions\DepositOrderAction;
use EuroMillions\shared\components\order_actions\TicketPurchaseOrderAction;
use EuroMillions\shared\components\order_actions\WithdrawOrderAction;
use EuroMillions\web\vo\enum\OrderType;
use EuroMillions\web\vo\Order;

class OrderActionContext
{

    private $strategy;


    public function __construct($status, Order $order, \Phalcon\Events\Manager $eventsManager)
    {
        switch($order->getOrderType())
        {
            case OrderType::TICKET_PURCHASE:
                $this->strategy=new TicketPurchaseOrderAction($status,$order,$eventsManager);
                break;
            case OrderType::DEPOSIT:
                $this->strategy=new DepositOrderAction($status,$order,$eventsManager);
                break;
            case OrderType::WINNINGS_WITHDRAW:
                $this->strategy=new WithdrawOrderAction($status,$order,$eventsManager);
                break;
        }
    }

    public function execute()
    {
        $this->strategy->execute();
    }

}