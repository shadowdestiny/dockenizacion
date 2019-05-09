<?php


namespace EuroMillions\web\services;

use EuroMillions\shared\components\OrderActionContext;
use EuroMillions\shared\components\PaymentsCollection;
use EuroMillions\shared\enums\PaymentProviderEnum;
use EuroMillions\web\components\CashierBuilderContext;
use EuroMillions\web\components\OrderDataPaymentProviderBuilderContext;
use EuroMillions\web\entities\User;
use EuroMillions\web\entities\WinningsWithdrawTransaction;
use EuroMillions\web\interfaces\IHandlerPaymentGateway;
use EuroMillions\web\interfaces\IPlayStorageStrategy;
use EuroMillions\web\services\card_payment_providers\ICreditCardStrategy;
use EuroMillions\web\services\criteria_strategies\CountryCriteria;
use EuroMillions\web\services\criteria_strategies\CriteriaSelector;
use EuroMillions\web\services\factories\CollectionPaymentCriteriaFactory;
use EuroMillions\web\vo\dto\ChasierDTO;
use EuroMillions\web\vo\dto\order_payment_provider\OrderPaymentProviderDTO;
use EuroMillions\web\vo\dto\UserDTO;
use EuroMillions\web\vo\dto\WithdrawResponseStatusDTO;
use EuroMillions\web\vo\enum\MoneyMatrixStatusCode;
use EuroMillions\web\vo\enum\OrderType;
use EuroMillions\web\vo\enum\PaymentSelectorType;
use EuroMillions\web\vo\enum\TransactionType;
use EuroMillions\web\vo\Order;
use EuroMillions\web\vo\PaymentCountry;
use Exception;
use Money\Money;
use Phalcon\Config;
use Phalcon\Events\EventsAwareInterface;
use Phalcon\Events\ManagerInterface;

class PaymentProviderService implements EventsAwareInterface
{

    /** @var  TransactionService $transactionService */
    protected $transactionService;

    /** @var \Phalcon\Events\Manager */
    protected $_eventsManager;

    /** @var IPlayStorageStrategy $redisOrderChecker */
    protected $redisOrderChecker;

    protected $paymentsCollection;

    public function __construct(TransactionService $transactionService, IPlayStorageStrategy $redisOrderChecker, PaymentsCollection $paymentsCollection)
    {
        $this->transactionService = $transactionService;
        $this->redisOrderChecker = $redisOrderChecker;
        $this->paymentsCollection = $paymentsCollection;
    }

    /**
     * @param PaymentCountry $country
     * @param $paymentSelectorType
     * @return PaymentsCollection
     */
    public function createCollectionFromTypeAndCountry($paymentSelectorType, PaymentCountry $country)
    {
        $newPaymentsCollection = CollectionPaymentCriteriaFactory::createCollectionFromSelectorCriteriaAndOtherCriteria(
            $this->paymentsCollection,
            new CriteriaSelector(new PaymentSelectorType($paymentSelectorType)),
            new CountryCriteria(PaymentCountry::createPaymentCountry($country->countries()))
        );

        return $newPaymentsCollection;
    }

    /**
     * @param string $providerName
     * @return ICreditCardStrategy
     */
    public function createCollectionFromProviderName($providerName)
    {
        $aPaymentProvider = CollectionPaymentCriteriaFactory::createADefaultPaymentProviderByName(
            $this->paymentsCollection,
            new PaymentProviderEnum($providerName)
        );

        return $aPaymentProvider;
    }

    public function cashier(IHandlerPaymentGateway $paymentMethod, OrderPaymentProviderDTO $orderData)
    {
        try {
            $hasOrder = $this->redisOrderChecker->findByKey($orderData->getUser()->getUserId());
            return (new CashierBuilderContext($paymentMethod, $orderData, $hasOrder))->builder()->build();
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param IHandlerPaymentGateway $paymentMethod
     * @param UserDTO $userDto
     * @param Order $order
     * @param array $data
     * @param Config $config
     * @return OrderPaymentProviderDTO $orderData
     * @throws Exception
     */
    public function orderDataPaymentProvider(IHandlerPaymentGateway $paymentMethod, UserDTO $userDto, Order $order, array $data, Config $config)
    {
        try {
            return (new OrderDataPaymentProviderBuilderContext($paymentMethod, $userDto, $order, $data, $config))->build();
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function checkHasOrderInProcessAndReturnCashierDTO(IHandlerPaymentGateway $paymentMethod, OrderPaymentProviderDTO $orderData)
    {
        $hasOrder = $this->redisOrderChecker->findByKey($orderData->getUser()->getUserId());
        if ($hasOrder->success()) {
            return new ChasierDTO($paymentMethod->type(), null, null, false, "Order processing...");
        }
    }

    public function withdrawStatus(IHandlerPaymentGateway $paymentMethod, $transactionID)
    {
        try {
            $response = $paymentMethod->call("", "withdraw/status/".$transactionID, 'get');
            return new WithdrawResponseStatusDTO(
                json_decode($response, true)
            );
        } catch (\Exception $e) {
        }
    }

    public function createOrUpdateDepositTransactionWithPendingStatus(Order $order, User $user, Money $amount, $paymentProviderName = null, $status = 'PENDING', $statusCode = '8')
    {
        try {
            $transactionId = $order->getTransactionId();
            $transaction = $this->transactionService->getTransactionByEmTransactionID($transactionId);

            //TODO: David -> with your refactor transaction builder
            $dataTransaction = [
                'lottery_id' => $order->getLottery() != null ? $order->getLottery()->getId() : 1,
                'numBets' => count($order->getPlayConfig()),
                'feeApplied' => $order->getCreditCardCharge()->getIsChargeFee(),
                'transactionID' => $transactionId,
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
                'withWallet' => $order->isIsCheckedWalletBalance() ? 1: 0,
                'accountBankId' => $user->getBankAccount(),
                'amountWithdrawed' => $amount->getAmount(),
                'state' => $status,
                'paymentProviderName' => $paymentProviderName != null ? $paymentProviderName : '',
            ];
            //TODO Complexity, a lot of conditionals
            if ($transaction == null) {
                if ($order->getHasSubscription()) {
                    $this->transactionService->storeTransaction(TransactionType::SUBSCRIPTION_PURCHASE, $dataTransaction);
                } elseif ($order->getOrderType() == OrderType::DEPOSIT) {
                    $this->transactionService->storeTransaction(TransactionType::DEPOSIT, $dataTransaction);
                } else {
                    $this->transactionService->storeTransaction(TransactionType::WINNINGS_WITHDRAW, $dataTransaction);
                }
            } else {
                //TODO Workaround. It should the same way as transaction builder
                if (!$transaction[0] instanceof WinningsWithdrawTransaction) {
                    $transaction[0]->setAmountAdded($order->getCreditCardCharge()->getFinalAmount()->getAmount());
                    $transaction[0]->setHasFee($order->getCreditCardCharge()->getIsChargeFee());
                    $transaction[0]->setLotteryId($order->getLottery()->getId());
                    $transaction[0]->setWithWallet($order->isIsCheckedWalletBalance() ? 1 :0);
                    $transaction[0]->setStatus($status);
                    $transaction[0]->setPaymentProviderName($paymentProviderName != null ? $paymentProviderName : '');
                } else {
                    $moneyMatrixStatus = new MoneyMatrixStatusCode();
                    $transaction[0]->setState($moneyMatrixStatus->getValue($statusCode));
                }
                $transaction[0]->setLotteryName($order->getLottery()->getName());
                $transaction[0]->toString();
                $this->transactionService->updateTransaction($transaction[0]);
                $orderActionContext = new OrderActionContext($status, $order, $transactionId, $this->_eventsManager, $statusCode);
                $orderActionContext->execute();
            }
        } catch (Exception $e) {
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
