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

        if($transaction->getLotteryName() == 'Deposit')
        {
            $depositLottery = new Lottery();
            $depositLottery->initialize([
                'id'               => 1,
                'name'             => 'Deposit',
                'active'           => 1,
                'frequency'        => 'freq',
                'draw_time'        => 'draw',
                'single_bet_price' => new Money(300, new Currency('EUR')),
            ]);
            $playconfig=new PlayConfig();
            $playconfig->setFrequency(1);
            $playconfig->setUser($transaction->getUser());
            $money=new Money(0, new Currency('EUR'));
            $amount=new Money(intval($transaction->getAmountAdded()), new Currency('EUR'));
            $order=OrderFactory::create([$playconfig], $money, $money, $money, new Discount(0, []), $depositLottery , 'Deposit', $transaction->getWithWallet());
            $order->addFunds($amount);
        }else
        {
            $result = $this->cartService->get($transaction->getUser()->getId(),$transaction->getLotteryName(), $transaction->getWithWallet());
            /** @var Order $order */
            $order = $result->getValues();
            try
            {
                $this->validations($transactionID,$status,$transaction,$logger, $order);
            } catch (\Exception $e)
            {
                throw new \Exception();
            }
        }
        $this->paymentProviderService->setEventsManager($this->eventsManager);
        $this->eventsManager->attach('orderservice', $this->orderService);
        $nextDrawForOrder = $this->lotteryService->getNextDrawByLottery($transaction->getLotteryName())->getValues();
        $order->setNextDraw($nextDrawForOrder);
        $this->paymentProviderService->createOrUpdateDepositTransactionWithPendingStatus($order,$transaction->getUser(),$order->getTotal(),$transactionID,$status);
    }


    private function validations($transactionID, $status,Transaction $transaction,CloudWatch $logger, Order $order=null)
    {
        if(empty($transactionID) or empty($status))
        {
            $logger->log(
                Logger::ERROR,
                'NotificationController:params are empty'
            );
            throw new \Exception('Params are empty');
        }

        if($transaction == null)
        {
            $logger->log(
                Logger::INFO,
                'NotificationController:transaction is null with id: ' . $transactionID
            );
            throw new \Exception();
        }

        if($status == 'ERROR' && $order != null)
        {
            $logger->log(
                Logger::INFO,
                'ERRORNotificationController:Notification error with transactionID: ' . $transactionID
            );
            $this->paymentProviderService->createOrUpdateDepositTransactionWithPendingStatus($order,$transaction->getUser(),$order->getTotal(),$transactionID,$status);
            $this->orderService->sendErrorEmail($order, $transaction->getDate());
            $logger->log(
                Logger::INFO,
                'ERRORNotificationController:sending email ' . $transactionID
            );
            throw new \Exception();
        }

        $transaction->fromString();
        if($transaction->getStatus() == 'SUCCESS')
        {
            $logger->log(
                Logger::INFO,
                'ERRORNotificationController:Transaction already in use' . $transactionID
            );

            throw new \Exception();
        }


    }
}