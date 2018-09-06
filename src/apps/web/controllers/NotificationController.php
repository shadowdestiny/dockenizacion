<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 25/08/18
 * Time: 13:54
 */

namespace EuroMillions\web\controllers;


use EuroMillions\shared\components\logger\cloudwatch\ConfigGenerator;
use EuroMillions\web\components\logger\Adapter\CloudWatch;
use EuroMillions\web\entities\Transaction;
use EuroMillions\web\services\PlayService;
use EuroMillions\web\vo\Order;
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

        if(empty($transactionID) or empty($status))
        {
            $logger->log(
                Logger::ERROR,
                'NotificationController:params are empty'
            );
            throw new \Exception('Params are empty');
        }
        /** @var Transaction $transaction */
        $transaction = $this->transactionService->getTransactionByEmTransactionID($transactionID)[0];
        if($transaction == null)
        {
            $logger->log(
                Logger::INFO,
                'NotificationController:transaction is null with id: ' . $transactionID
            );
            throw new \Exception();
        }
        $transaction->fromString();

        $result = $this->cartService->get($transaction->getUser()->getId(),$transaction->getLotteryName(), $transaction->getWithWallet());
        /** @var Order $order */
        $order = $result->getValues();

        if($transaction->getStatus() == 'ERROR')
        {
            $logger->log(
                Logger::INFO,
                'ERRORNotificationController:Notification error with transactionID: ' . $transactionID
            );
            $this->orderService->sendErrorEmail($order,     $transaction->getDate());
            $logger->log(
                Logger::INFO,
                'ERRORNotificationController:sending email ' . $transactionID
            );
            throw new \Exception();
        }

        $this->paymentProviderService->setEventsManager($this->eventsManager);
        $this->eventsManager->attach('orderservice', $this->orderService);
        $nextDrawForOrder = $this->lotteryService->getNextDrawByLottery($transaction->getLotteryName())->getValues();
        $order->setNextDraw($nextDrawForOrder);

        $this->paymentProviderService->createOrUpdateDepositTransactionWithPendingStatus($order,$transaction->getUser(),$order->getTotal(),$transactionID,$status);
    }
}