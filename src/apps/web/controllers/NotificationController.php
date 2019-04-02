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
use EuroMillions\web\entities\Transaction;
use LegalThings\CloudWatchLogger;
use Phalcon\Mvc\View;

class NotificationController extends MoneymatrixController
{
    public function beforeExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {
        $token = $this->request->getHeader('Authorization');
        if (!$this->validateToken($token)) {
            $this->response->setStatusCode(401);
            $this->response->send();
            return false;
        }

        return true;
    }

    //TODO: send to queue
    public function notificationAction()
    {
        $transactionID = $this->request->getQuery('transaction');
        $status = $this->request->getQuery('status');
        $statusCode = $this->request->getQuery('statusCode');
        $testDate = !empty($this->request->getQuery('date')) ? new \DateTime($this->request->getQuery('date')) : null;

        $logger = new CloudWatch(new CloudWatchLogger(ConfigGenerator::cloudWatchConfig(
            'Euromillions',
            getenv('EM_ENV')
        )));
        /** @var Transaction $transaction */
        $transaction = $this->transactionService->getTransactionByEmTransactionID($transactionID)[0];
        $transaction->fromString();

        $orderNotification = new OrderNotificationContext($transaction, $logger, $this->cartService);
        $order = $orderNotification->execute();
        $orderNotificationValidator = new ValidatorOrderNotificationsContext(
            $transaction,
            $status,
            $order,
            $this->paymentProviderService,
            $this->orderService,
            $logger
        );
        if ($orderNotificationValidator->result()) {
            $this->paymentProviderService->setEventsManager($this->eventsManager);
            $this->eventsManager->attach('orderservice', $this->orderService);
            $nextDrawForOrder = $this->lotteryService->getNextDrawByLottery($transaction->getLotteryName(), !empty($testDate) ? $testDate : null)->getValues();
            $order->setNextDraw($nextDrawForOrder);
            $this->paymentProviderService->createOrUpdateDepositTransactionWithPendingStatus($order, $transaction->getUser(), $order->getTotal(), $status, $statusCode);
        }

        exit();
    }

    public function statusAction()
    {
        try {
            $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
            $this->response->setHeader('Content-type', 'application/json');
            $data = $this->request->getJsonRawBody();
            $response = $this->paymentProviderService->withdrawStatus($this->getDI()->get('paymentProviderFactory'), $data->MerchantReference);
            echo json_encode($response);
        } catch (\Exception $e) {
            $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
            $this->response->setStatusCode(500);
            $this->response->setHeader('Content-type', 'application/json');
            echo json_encode([
                'Status' => 'Error'
            ]);
        }
    }

    private function validateToken($token)
    {
        if (getenv('EM_ENV') == 'test' || getenv('EM_ENV') == 'development') {
            return true;
        }

        if ($token !== null && $token != '' && $token === (string) $this->di->get('config')['payments_notifications']['token']) {
            return true;
        }

        return false;
    }

}
