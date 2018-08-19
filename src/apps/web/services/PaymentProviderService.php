<?php


namespace EuroMillions\web\services;


use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\interfaces\IHandlerPaymentGateway;
use EuroMillions\web\vo\dto\ChasierDTO;
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
        try {
            $transactionID = $this->transactionService->getUniqueTransactionId();
            $orderData->setTransactionID($transactionID);
            $orderData->exChangeObject();
            $response = $paymentMethod->call($orderData->toJson());
            return new ChasierDTO(json_decode($response, true));
        } catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }
}