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
        $lottery = $order->getLottery();
        $this->redisOrderChecker->save($transactionID,$user->getId());
        try
        {
            if($order->isNextDraw())
            {
                $walletBefore = $user->getWallet();
                $user=$this->updateOrderTransaction($user, $order, $transactionID, $walletBefore);
                $walletBefore = $user->getWallet();
                foreach ($order->getPlayConfig() as $playConfig)
                {
                    $playConfig->setLottery($order->getLottery());
                    $playConfig->setDiscount(new Discount($order->getPlayConfig()[0]->getFrequency(),$this->playService->retrieveEuromillionsBundlePrice()));
                    $result = $this->playService->validatorResult($lottery,$playConfig,new ActionResult(true, $order->getNextDraw()),$order);
                    $isBetPersisted = $this->playService->persistBetDistinctEuroMillions($playConfig, new ActionResult(true, $order->getNextDraw()), $order, $result->getValues());
                    $this->logger->log(Logger::INFO,
                        'checkout:Validating against lottery provider with status =' . $result->success());
                    if($result->success() && $isBetPersisted->success())
                    {
                        $this->logger->log(Logger::INFO,
                            'checkout:Before substract playconfig bet value from wallet =' . $user->getBalance()->getAmount());
                       $this->walletService->extract($user,$order);
                        $this->logger->log(Logger::INFO,
                            'checkout:After substract playconfig bet value from wallet =' . $user->getBalance()->getAmount());
                    }
                }
                //TODO move to TransactionService
                $dataTransaction = [
                    'lottery_id' => $order->getLottery()->getId(),
                    'transactionID' => $transactionID,
                    'numBets' => count($order->getPlayConfig()),
                    'feeApplied' => $order->getCreditCardCharge()->getIsChargeFee(),
                    'amountWithWallet' => $order->amountForTicketPurchaseTransaction(),
                    'walletBefore' => $walletBefore,
                    'amountWithCreditCard' => 0,
                    'playConfigs' => array_map(function ($val) {
                        return $val->getId();
                    }, $order->getPlayConfig()),
                    'discount' => $order->getDiscount()->getValue(),
                ];
                $this->walletService->purchaseTransactionGrouped($user, TransactionType::TICKET_PURCHASE, $dataTransaction);
                $this->logger->log(Logger::INFO,
                    'checkout:Transaction TICKET_PURCHASE it was created');
                $this->sendEmail($user,$order,$lottery->getName());
                $this->redisOrderChecker->delete($user->getId());
                $this->logger->log(Logger::INFO,
                    'checkout:Email sent');
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
        $this->logger->log(Logger::INFO,
            'checkout:New withdraw order with transactionID= ' . $data['transactionID']);

        /** @var Order $order */
        $order = $data['order'];
        $transactionID = $data['transactionID'];
        /** @var User $user */
        $user = $order->getPlayConfig()[0]->getUser();
        $this->redisOrderChecker->save($transactionID,$user->getId());
        try
        {
            $user->getWallet()->withdraw();
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

    private function sendEmail(User $user, Order $order, $lotteryName)
    {
        if($lotteryName == 'EuroMillions')
        {
            $this->playService->sendEmailPurchase($user,$order->getPlayConfig());
        }
        if($lotteryName == 'PowerBall')
        {
            $this->playService->sendEmailPowerBallPurchase($user,$order->getPlayConfig());
        }
    }

    private function updateOrderTransaction($user, $order, $transactionID, $walletBefore)
    {
        $order->getPlayConfig()[0]->setLottery($order->getLottery());
        $this->logger->log(Logger::INFO,
            'checkout:User wallet before it was payed=' . $user->getWallet()->getBalance()->getAmount());
        $user = $this->walletService->payOrder($user,$order);
        $this->logger->log(Logger::INFO,
            'checkout:User it was payed in its wallet=' . $user->getWallet()->getBalance()->getAmount());
        $transactions = $this->transactionService->getTransactionByEmTransactionID($transactionID);

        //TODO move to TransactionService
        $transactions[0]->fromString();
        $transactions[0]->setWalletBefore($walletBefore);
        $transactions[0]->setWalletAfter($user->getWallet());
        $transactions[0]->toString();
        $this->transactionService->updateTransaction($transactions[0]);

        return $user;
    }
}