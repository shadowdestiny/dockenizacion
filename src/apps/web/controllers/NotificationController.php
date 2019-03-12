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
use Phalcon\Mvc\View;

class NotificationController extends MoneymatrixController
{


    //TODO: send to queue
    public function notificationAction()
    {


        error_log(print_r($this->request->getJsonRawBody(true), TRUE),0);
        $transactionID = $this->request->getQuery('transaction');
        $status = $this->request->getQuery('status');
        $statusCode = $this->request->getQuery('statusCode');

        $logger = new CloudWatch(new CloudWatchLogger(ConfigGenerator::cloudWatchConfig(
            'Euromillions', getenv('EM_ENV')
        )));
        /** @var Transaction $transaction */
        $transaction = $this->transactionService->getTransactionByEmTransactionID($transactionID)[0];
        $transaction->fromString();


        $orderNotification = new OrderNotificationContext($transaction,$logger,$this->cartService);
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
            $this->paymentProviderService->createOrUpdateDepositTransactionWithPendingStatus($order,$transaction->getUser(),$order->getTotal(),$transactionID,$status,$statusCode);
        }
    }


    public function statusAction()
    {
        try
        {
            $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
            $this->response->setHeader('Content-type','application/json');
            $data = $this->request->getJsonRawBody();
            $response = $this->paymentProviderService->withdrawStatus($this->getDI()->get('paymentProviderFactory'),$data->MerchantReference);
            echo json_encode($response);
        } catch(\Exception $e)
        {
            $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
            $this->response->setStatusCode(500);
            $this->response->setHeader('Content-type','application/json');
            echo json_encode([
                'Status' => 'Error'
            ]);
        }
    }

}