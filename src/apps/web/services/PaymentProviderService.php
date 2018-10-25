<?php


namespace EuroMillions\web\services;


use EuroMillions\shared\components\logger\cloudwatch\ConfigGenerator;
use EuroMillions\shared\components\OrderActionContext;
use EuroMillions\web\components\logger\Adapter\CloudWatch;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\interfaces\IHandlerPaymentGateway;
use EuroMillions\web\interfaces\IPlayStorageStrategy;
use EuroMillions\web\vo\dto\ChasierDTO;
use EuroMillions\web\vo\dto\OrderPaymentProviderDTO;
use EuroMillions\web\vo\enum\TransactionType;
use EuroMillions\web\vo\Order;
use Exception;
use LegalThings\CloudWatchLogger;
use Money\Money;
use Phalcon\Events\EventsAwareInterface;
use Phalcon\Events\ManagerInterface;
use Phalcon\Logger;

class PaymentProviderService implements EventsAwareInterface
{

    /** @var  TransactionService $transactionService */
    protected $transactionService;

    /** @var \Phalcon\Events\Manager */
    protected $_eventsManager;

    /** @var IPlayStorageStrategy $redisOrderChecker */
    protected $redisOrderChecker;

    public function __construct(TransactionService $transactionService, IPlayStorageStrategy $redisOrderChecker)
    {
        $this->transactionService = $transactionService;
        $this->redisOrderChecker = $redisOrderChecker;
    }

    public function getCashierViewDTOFromMoneyMatrix(IHandlerPaymentGateway $paymentMethod, OrderPaymentProviderDTO $orderData, $transactionID=null)
    {
        try {
            $hasOrder = $this->redisOrderChecker->findByKey($orderData->user->getId());
            if($hasOrder->success())
            {
                return new ChasierDTO(null,null,"Order processing...");
            }
            if($transactionID == null)
            {
                $transactionID = $this->transactionService->getUniqueTransactionId();
            }
            $orderData->setTransactionID($transactionID);
            $orderData->exChangeObject();
            $response = $paymentMethod->call($orderData->toJson());
            return new ChasierDTO(json_decode($response, true),$transactionID);
        } catch (\Exception $e)
        {
            throw new Exception($e->getMessage());
        }
    }

    public function createOrUpdateDepositTransactionWithPendingStatus(Order $order, User $user,Money $amount, $transactionID, $status='PENDING')
    {
        try
        {
            $transaction = $this->transactionService->getTransactionByEmTransactionID($transactionID);
            $dataTransaction = [
                'lottery_id' => $order->getLottery() != null ? $order->getLottery()->getId() : 1,
                'numBets' => count($order->getPlayConfig()),
                'feeApplied' => $order->getCreditCardCharge()->getIsChargeFee(),
                'transactionID' => $transactionID,
                'amountWithWallet' => 0,
                'playConfigs' => $order ? $order->getPlayConfig()[0]->getId() : 0,
                'amount' => $amount->getAmount(),
                'amountWithCreditCard' => $amount->getAmount(),
                'user' => $user,
                'walletBefore' => $user->getWallet(),
                'walletAfter' => $user->getWallet(),
                'now' => new \DateTime(),
                'status' => $status,
                'lotteryName' => $order->getLottery()->getName(),
                'withWallet' => $order->isIsCheckedWalletBalance() ? 1: 0
            ];
            if($transaction == null)
            {
                if($order->getHasSubscription())
                {
                    $this->transactionService->storeTransaction(TransactionType::SUBSCRIPTION_PURCHASE, $dataTransaction);
                } else {
                    $this->transactionService->storeTransaction(TransactionType::DEPOSIT, $dataTransaction);
                }
            } else {
                $transaction[0]->setAmountAdded($order->getCreditCardCharge()->getFinalAmount()->getAmount());
                $transaction[0]->setStatus($status);
                $transaction[0]->setHasFee($order->getCreditCardCharge()->getIsChargeFee());
                $transaction[0]->setLotteryId($order->getLottery()->getId());
                $transaction[0]->setLotteryName($order->getLottery()->getName());
                $transaction[0]->setWithWallet($order->isIsCheckedWalletBalance() ? 1 :0);
                $transaction[0]->toString();
                $this->transactionService->updateTransaction($transaction[0]);

                if($status == 'SUCCESS' && !$order->isDepositOrder())
                {
                    $this->_eventsManager->fire('orderservice:checkout', $this, ["order" => $order,
                                                                                           "transactionID" => $transactionID
                            ]
                    );
                }
                elseif($status == 'SUCCESS' && $order->isDepositOrder())
                {
                    $this->_eventsManager->fire('orderservice:addDepositFounds', $this, ["order" => $order,
                            "transactionID" => $transactionID
                        ]
                    );
                }
            }
        } catch( Exception $e )
        {

        }
    }

    /**
     * Sets the events manager
     *
     * @param mixed $eventsManager
     */
    public function setEventsManager(ManagerInterface $eventsManager)
    {
        $this->_eventsManager = $eventsManager;
    }

    /**
     * Returns the internal event manager
     *
     * @return ManagerInterface
     */
    public function getEventsManager()
    {
        return $this->_eventsManager;
    }
}