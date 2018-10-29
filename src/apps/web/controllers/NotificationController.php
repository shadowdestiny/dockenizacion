<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 25/08/18
 * Time: 13:54
 */

namespace EuroMillions\web\controllers;


use EuroMillions\shared\components\logger\cloudwatch\ConfigGenerator;
use EuroMillions\shared\components\OrderNotificationContext;
use EuroMillions\shared\components\ValidatorOrderNotificationsContext;
use EuroMillions\web\components\logger\Adapter\CloudWatch;
use EuroMillions\web\entities\DepositTransaction;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\Transaction;
use EuroMillions\web\services\factories\OrderFactory;
use EuroMillions\web\vo\Discount;
use Money\Currency;
use Money\Money;
use EuroMillions\web\vo\Order;
use EuroMillions\web\entities\PlayConfig;
use LegalThings\CloudWatchLogger;
use Phalcon\Logger;

class NotificationController extends MoneymatrixController
{


    //TODO: send to queue
    public function notificationAction()
    {

        $transactionID = $this->request->getQuery('transaction');
        $status = $this->request->getQuery('status');

        $logger = new CloudWatch(new CloudWatchLogger(ConfigGenerator::cloudWatchConfig(
            'Euromillions', getenv('EM_ENV')
        )));
        /** @var Transaction $transaction */
        $transaction = $this->transactionService->getTransactionByEmTransactionID($transactionID)[0];
        $transaction->fromString();


        $orderNotification = new OrderNotificationContext($status,$transactionID,$transaction,$logger,$this->cartService);
        $order = $orderNotification->execute();
        $orderNotificationValidator = new ValidatorOrderNotificationsContext($transaction,
                                                                             $status,
                                                                             $order,
                                                                             $this->paymentProviderService,
                                                                             $this->orderService,
                                                                             $logger
        );

        if($orderNotificationValidator->result())
        {
            $this->paymentProviderService->setEventsManager($this->eventsManager);
            $this->eventsManager->attach('orderservice', $this->orderService);
            $nextDrawForOrder = $this->lotteryService->getNextDrawByLottery($transaction->getLotteryName())->getValues();
            $order->setNextDraw($nextDrawForOrder);
            $this->paymentProviderService->createOrUpdateDepositTransactionWithPendingStatus($order,$transaction->getUser(),$order->getTotal(),$transactionID,$status);
        }
    }

}