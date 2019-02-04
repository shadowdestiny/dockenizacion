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

    protected $transactionID;

    /** @var \Phalcon\Events\Manager $eventsManager */
    protected $eventsManager;

    public function __construct($status, Order $order, $transactionID, \Phalcon\Events\Manager $eventsManager)
    {
        $this->status=$status;
        $this->order=$order;
        $this->transactionID=$transactionID;
        $this->eventsManager=$eventsManager;
    }

    public function execute()
    {
        if($this->status == 'SUCCESS')
        {
            $this->eventsManager->fire('orderservice:addDepositFounds', $this, ["order" => $this->order,
                                                                                          "transactionID" => $this->transactionID
                ]
            );
        }
    }
}