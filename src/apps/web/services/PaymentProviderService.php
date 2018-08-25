<?php


namespace EuroMillions\web\services;


use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\interfaces\IHandlerPaymentGateway;
use EuroMillions\web\vo\dto\ChasierDTO;
use EuroMillions\web\vo\dto\OrderPaymentProviderDTO;
use EuroMillions\web\vo\enum\TransactionType;
use EuroMillions\web\vo\Order;
use Exception;
use Money\Money;
use Phalcon\Events\EventsAwareInterface;
use Phalcon\Events\ManagerInterface;

class PaymentProviderService implements EventsAwareInterface
{

    /** @var  TransactionService $transactionService */
    protected $transactionService;

    /** @var \Phalcon\Events\Manager */
    protected $_eventsManager;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function getCashierViewDTOFromMoneyMatrix(IHandlerPaymentGateway $paymentMethod, OrderPaymentProviderDTO $orderData, $transactionID=null)
    {
        try {
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
                'numBets' => count($user->getPlayConfig()),
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
                'status' => $status
            ];
            if($transaction == null)
            {
                $this->transactionService->storeTransaction(TransactionType::DEPOSIT, $dataTransaction);
            } else {
                $transaction[0]->setAmountAdded($amount->getAmount());
                $transaction[0]->setStatus($status);
                $transaction[0]->setHasFee($order->getCreditCardCharge()->getIsChargeFee());
                $transaction[0]->toString();
                $this->transactionService->updateTransaction($transaction[0]);
                if($status == 'SUCCESS')
                {
                    $this->_eventsManager->fire('cartservice:checkout', $this, $order);
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