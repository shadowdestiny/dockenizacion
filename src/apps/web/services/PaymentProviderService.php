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

class PaymentProviderService
{

    /** @var  TransactionService $transactionService */
    protected $transactionService;

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

    public function createOrUpdateDepositTransactionWithPendingStatus(Order $order, User $user,Money $amount, $transactionID)
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
                'status' => 'PENDING'
            ];
            if($transaction == null)
            {
                $this->transactionService->storeTransaction(TransactionType::DEPOSIT, $dataTransaction);
            } else {
                $transaction[0]->setAmountAdded($amount->getAmount());
                $transaction[0]->setStatus('PENDING');
                $transaction[0]->setHasFee($order->getCreditCardCharge()->getIsChargeFee());
                $transaction[0]->toString();
                $this->transactionService->updateTransaction($transaction[0]);
            }

        } catch( Exception $e )
        {

        }
    }
}