<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 12/11/18
 * Time: 9:30
 */

namespace EuroMillions\shared\components\validations_order_notifications;


use EuroMillions\shared\interfaces\IValidatorOrderNotifications;
use EuroMillions\web\components\logger\Adapter\CloudWatch;
use EuroMillions\web\entities\Transaction;
use EuroMillions\web\services\OrderService;
use EuroMillions\web\services\PaymentProviderService;
use EuroMillions\web\vo\Order;

class WithdrawNotificationValidator implements IValidatorOrderNotifications
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
        $this->transaction->fromString();
        if($this->transaction->getStatus() == 'PENDING_APPROVAL' || $this->transaction->getStatus() == 'SUCCESS')
        {
            $this->logger->log(
                Logger::INFO,
                'ERRORNotificationController:Transaction already in use' . $this->transaction->getId()
            );

            throw new \Exception();
        }
    }
}