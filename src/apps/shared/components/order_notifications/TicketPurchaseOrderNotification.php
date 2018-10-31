<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 29/10/18
 * Time: 10:41
 */

namespace EuroMillions\shared\components\order_notifications;


use EuroMillions\shared\interfaces\IOrderNotificationBuilder;
use EuroMillions\web\components\logger\Adapter\CloudWatch;
use EuroMillions\web\entities\Transaction;
use EuroMillions\web\services\CartService;
use EuroMillions\web\vo\enum\OrderType;
use EuroMillions\web\vo\Order;

class TicketPurchaseOrderNotification implements IOrderNotificationBuilder
{

    private $cartService;

    private $transaction;

    private $order;

    private $logger;

    public function __construct(CartService $cartService, Transaction $transaction, CloudWatch $logger)
    {
        $this->cartService=$cartService;
        $this->transaction=$transaction;
        $this->logger=$logger;
    }

    public function giveMeOrder()
    {
        try
        {
            $this->build();
            return $this->order;
        }catch(\Exception $e)
        {
            return null;
        }
    }

    protected function build()
    {
        $this->transaction->fromString();
        $result = $this->cartService->get($this->transaction->getUser()->getId(),$this->transaction->getLotteryName(), $this->transaction->getWithWallet());
        /** @var Order $order */
        $order = $result->getValues();
        try
        {
            $order->setOrderType(OrderType::TICKET_PURCHASE);
            $this->order = $order;
        } catch (\Exception $e)
        {
            throw new \Exception();
        }
    }

}