<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 29/10/18
 * Time: 11:18
 */

namespace EuroMillions\shared\components\validations_order_notifications;


use EuroMillions\shared\interfaces\IValidatorOrderNotifications;
use EuroMillions\web\components\logger\Adapter\CloudWatch;
use EuroMillions\web\entities\Transaction;
use EuroMillions\web\services\OrderService;
use EuroMillions\web\services\PaymentProviderService;
use EuroMillions\web\vo\Order;

class TicketPurchaseNotificationValidator implements IValidatorOrderNotifications
{

    private $order;

    private $status;

    private $transaction;

    private $paymentProviderService;

    private $orderService;

    private $logger;

    public function __construct(Order $order,
                                $status,
                                Transaction $transaction,
                                PaymentProviderService $paymentProviderService,
                                OrderService $orderService,
                                CloudWatch $logger)
    {
        $this->order=$order;
        $this->status=$status;
        $this->transaction=$transaction;
        $this->paymentProviderService=$paymentProviderService;
        $this->orderService=$orderService;
        $this->logger=$logger;
    }


    public function validate()
    {
        try
        {
            $this->validateThisNotification();
            return true;
        } catch(\Exception $e)
        {
            return false;
        }
    }


    private function validateThisNotification()
    {
        if(empty($this->transaction->getId()) or empty($status))
        {
            $this->logger->log(
                Logger::ERROR,
                'NotificationController:params are empty'
            );
            throw new \Exception('Params are empty');
        }

        if($this->transaction == null)
        {
            $this->logger->log(
                Logger::INFO,
                'NotificationController:transaction is null with id: ' . $this->transaction->getId()
            );
            throw new \Exception();
        }

        if($this->status == 'ERROR' && $this->order != null)
        {
            $this->logger->log(
                Logger::INFO,
                'ERRORNotificationController:Notification error with transactionID: ' . $this->transaction->getId()
            );
            $this->paymentProviderService->createOrUpdateDepositTransactionWithPendingStatus($this->order,$this->transaction->getUser(),$this->order->getTotal(),$this->transaction->getId(),$this->status);
            $this->orderService->sendErrorEmail($this->order, $this->transaction->getDate());
            $this->logger->log(
                Logger::INFO,
                'ERRORNotificationController:sending email ' . $this->transaction->getId()
            );
            throw new \Exception();
        }

        $this->transaction->fromString();
        if($this->transaction->getStatus() == 'SUCCESS')
        {
            $this->logger->log(
                Logger::INFO,
                'ERRORNotificationController:Transaction already in use' . $this->transaction->getId()
            );

            throw new \Exception();
        }


    }


}