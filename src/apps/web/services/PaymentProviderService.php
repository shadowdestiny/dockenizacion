<?php


namespace EuroMillions\web\services;


use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\interfaces\IHandlerPaymentGateway;
use EuroMillions\web\vo\dto\OrderPaymentProviderDTO;
use Money\Money;

class PaymentProviderService
{

    /** @var  TransactionService $transactionService */
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function getCashierViewDTOFromMoneyMatrix(IHandlerPaymentGateway $paymentMethod, OrderPaymentProviderDTO $orderData)
    {

        $transactionID = $this->transactionService->getUniqueTransactionId();
        $orderData->setTransactionID($transactionID);
        $dto = $paymentMethod->call($orderData->toJson());
        return $dto;
    }
}