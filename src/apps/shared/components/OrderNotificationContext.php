<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 29/10/18
 * Time: 10:19
 */

namespace EuroMillions\shared\components;


use EuroMillions\shared\components\order_actions\TicketPurchaseOrderAction;
use EuroMillions\shared\components\order_notifications\DepositOrderNotification;
use EuroMillions\shared\components\order_notifications\TicketPurchaseOrderNotification;
use EuroMillions\shared\components\order_notifications\WithdrawOrderNotification;
use EuroMillions\web\components\logger\Adapter\CloudWatch;
use EuroMillions\web\entities\Transaction;
use EuroMillions\web\services\CartService;
use EuroMillions\web\vo\Order;

class OrderNotificationContext
{

    private $strategy;


    public function __construct(Transaction $transaction,CloudWatch $logger, CartService $cartService)
    {
        switch($transaction->getLotteryName())
        {
            case 'Deposit':
                $this->strategy=new DepositOrderNotification($transaction);
                break;
            case 'Withdraw':
                $this->strategy=new WithdrawOrderNotification($transaction);
                break;
            default:
                $this->strategy=new TicketPurchaseOrderNotification($cartService,$transaction, $logger);

        }

    }

    public function execute()
    {
        return $this->strategy->giveMeOrder();
    }


}