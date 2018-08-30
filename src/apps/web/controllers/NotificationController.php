<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 25/08/18
 * Time: 13:54
 */

namespace EuroMillions\web\controllers;


use EuroMillions\web\entities\Transaction;
use EuroMillions\web\services\PlayService;
use EuroMillions\web\vo\Order;

class NotificationController extends MoneymatrixController
{


    //TODO: send to queue
    public function notificationAction()
    {
        $transactionID = $this->request->getPost('transaction');
        $status = $this->request->getPost('status');
        /** @var Transaction $transaction */
        $transaction = $this->transactionService->getTransactionByEmTransactionID($transactionID)[0];
        if($transaction == null)
        {
            throw new \Exception();
        }
        $transaction->fromString();
        if($transaction->getStatus() != 'PENDING')
        {
            throw new \Exception();
        }
        $result = $this->cartService->get($transaction->getUser()->getId(),$transaction->getLotteryName(), $transaction->getWithWallet());
        /** @var Order $order */
        $order = $result->getValues();
        $this->paymentProviderService->setEventsManager($this->eventsManager);
        $this->eventsManager->attach('orderservice', $this->orderService);
        $nextDrawForOrder = $this->lotteryService->getNextDrawByLottery($transaction->getLotteryName())->getValues();
        $order->setNextDraw($nextDrawForOrder);
        $this->paymentProviderService->createOrUpdateDepositTransactionWithPendingStatus($order,$transaction->getUser(),$order->getTotal(),$transactionID,$status);
    }
}