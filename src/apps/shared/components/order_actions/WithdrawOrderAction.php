<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 25/10/18
 * Time: 12:45
 */

namespace EuroMillions\shared\components\order_actions;


use EuroMillions\shared\interfaces\IOrderAction;
use EuroMillions\web\vo\enum\MoneyMatrixStatusCode;
use EuroMillions\web\vo\Order;

final class WithdrawOrderAction implements IOrderAction
{

    protected $status;

    /** @var Order $order */
    protected $order;

    protected $transactionID;

    /** @var \Phalcon\Events\Manager $eventsManager */
    protected $eventsManager;

    protected $statusCode;

    public function __construct($status, Order $order, $transactionID, \Phalcon\Events\Manager $eventsManager, $statusCode)
    {
        $this->status=$status;
        $this->order=$order;
        $this->transactionID=$transactionID;
        $this->eventsManager=$eventsManager;
        $this->statusCode=$statusCode;
    }

    public function execute()
    {

        $moneyMatrixStatusCode = new MoneyMatrixStatusCode();
        $statusCode = $moneyMatrixStatusCode->getValue($this->statusCode);

        if($statusCode == "CANCELED" || $statusCode == "REJECTED")
        {
            $this->eventsManager->fire('orderservice:revertWithdraw',
                $this,
                [
                    "order" => $this->order,
                    "transactionID" => $this->transactionID
                ]
            );
        }

        if( $statusCode == "PENDING_APPROVAL")
        {
            $this->eventsManager->fire('orderservice:withdraw',
                                                 $this,
                [
                                                "order" => $this->order,
                                                "transactionID" => $this->transactionID
                ]
            );
        }
    }
}