<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 29/10/18
 * Time: 11:23
 */

namespace EuroMillions\shared\components;


use EuroMillions\shared\components\validations_order_notifications\TicketPurchaseNotificationValidator;
use EuroMillions\web\components\logger\Adapter\CloudWatch;
use EuroMillions\web\entities\Transaction;
use EuroMillions\web\services\OrderService;
use EuroMillions\web\services\PaymentProviderService;
use EuroMillions\web\vo\enum\OrderType;
use EuroMillions\web\vo\Order;
use LegalThings\CloudWatchLogger;

class ValidatorOrderNotificationsContext
{


    private $strategy;

    public function __construct(Transaction $transaction,
                                $status,
                                Order $order,
                                PaymentProviderService $paymentProviderService,
                                OrderService $orderService,
                                CloudWatch $logger)
    {

        if($order->getOrderType() == OrderType::TICKET_PURCHASE)
        {
            $this->strategy = new TicketPurchaseNotificationValidator($order);
        }

    }

    public function result()
    {
        $this->strategy->validate();
    }

}