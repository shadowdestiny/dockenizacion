<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 27/08/18
 * Time: 12:14
 */

namespace EuroMillions\web\services;


use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\components\logger\Adapter\CloudWatch;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\IPlayStorageStrategy;
use EuroMillions\web\services\notification_mediator\NotificationMediator;
use EuroMillions\web\vo\Discount;
use EuroMillions\web\vo\enum\TransactionType;
use EuroMillions\web\vo\Order;
use Phalcon\Events\EventsAwareInterface;
use Phalcon\Events\ManagerInterface;
use Phalcon\Logger;

class OrderService
{

    /** @var PlayService $playService */
    protected $playService;

    protected $walletService;

    protected $transactionService;

    protected $logger;

    /** @var IPlayStorageStrategy $redisOrderChecker*/
    protected $redisOrderChecker;

    protected $mediator;


    public function __construct(WalletService $walletService,
                                PlayService $playService,
                                TransactionService $transactionService,
                                CloudWatch $logger,
                                IPlayStorageStrategy $redisOrderChecker
    )
    {
        $this->playService = $playService;
        $this->walletService = $walletService;
        $this->transactionService = $transactionService;
        $this->logger = $logger;
        $this->redisOrderChecker = $redisOrderChecker;
        $this->mediator= new NotificationMediator($this->walletService,$this->playService,$this->transactionService,(new LoggerFactory(""))->paymentStream());
    }


    public function checkout($event,$component,array $data)
    {

        $this->logger->log(Logger::INFO,
            'checkout:New checkout order with transactionID= ' . $data['transactionID']);

        /** @var Order $order */
        $order = $data['order'];
        $transactionID = $data['transactionID'];
        /** @var User $user */
        $user = $order->getPlayConfig()[0]->getUser();
        $this->redisOrderChecker->save($transactionID,$user->getId());
        try
        {
            if($order->isNextDraw())
            {
                $walletBefore = $user->getWallet();
                $this->walletService->initPaymentFlowFromNotification($user,$order,$transactionID,$walletBefore);
                $this->redisOrderChecker->delete($user->getId());
            }
        } catch(\Exception $e)
        {
            $this->redisOrderChecker->delete($user->getId());
            $this->logger->log(Logger::EMERGENCE,
                'ERRORcheckout:' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    public function hasOrderProcessing($userId)
    {
        return $this->redisOrderChecker->findByKey($userId)->success();
    }

    public function sendErrorEmail(Order $order, $dateOrder)
    {
        $user = $order->getPlayConfig()[0]->getUser();
        $this->playService->sendErrorEmail($user, $order, $dateOrder);
    }

    public function addDepositFounds($event,$component,array $data)
    {
        $this->logger->log(Logger::INFO,
            'checkout:New deposit order with transactionID= ' . $data['transactionID']);

        /** @var Order $order */
        $order = $data['order'];
        $transactionID = $data['transactionID'];
        /** @var User $user */
        $user = $order->getPlayConfig()[0]->getUser();
        $this->redisOrderChecker->save($transactionID,$user->getId());
        try
        {
                $walletBefore = $user->getWallet();
                $user=$this->updateOrderTransaction($user, $order, $transactionID, $walletBefore);
                $this->redisOrderChecker->delete($user->getId());
        } catch(\Exception $e)
        {
            $this->redisOrderChecker->delete($user->getId());
            $this->logger->log(Logger::EMERGENCE,
                'ERRORcheckout:' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    public function withDraw($event,$component,array $data)
    {
        /** @var Order $order */
        $order = $data['order'];
        $transactionID = $data['transactionID'];
        $user = $order->getPlayConfig()[0]->getUser();
        try
        {
            $walletBefore = $user->getWallet();
            $this->walletService->withDraw($user,$order->getCreditCardCharge()->getNetAmount());
            $transactions = $this->transactionService->getTransactionByEmTransactionID($transactionID);
            $transactions[0]->fromString();
            $transactions[0]->setWalletBefore($walletBefore);
            $transactions[0]->setWalletAfter($user->getWallet());
            $transactions[0]->toString();
            $this->transactionService->updateTransaction($transactions[0]);
        }catch(\Exception $e)
        {

        }
    }

    public function revertWithdraw($event,$component,array $data)
    {
        /** @var Order $order */
        $order = $data['order'];
        $transactionID = $data['transactionID'];
        $user = $order->getPlayConfig()[0]->getUser();
        try
        {
            $walletBefore = $user->getWallet();
            $this->walletService->addToWithdraw($user,$order->getCreditCardCharge()->getNetAmount());
            $transactions = $this->transactionService->getTransactionByEmTransactionID($transactionID);
            $transactions[0]->fromString();
            $transactions[0]->setWalletBefore($walletBefore);
            $transactions[0]->setWalletAfter($user->getWallet());
            $transactions[0]->toString();
            $this->transactionService->updateTransaction($transactions[0]);

        }catch(\Exception $e)
        {

        }
    }
}