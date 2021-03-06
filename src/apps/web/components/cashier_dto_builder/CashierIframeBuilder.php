<?php


namespace EuroMillions\web\components\cashier_dto_builder;

use EuroMillions\web\interfaces\ICashierDTOBuilder;
use EuroMillions\web\interfaces\IHandlerPaymentGateway;
use EuroMillions\web\vo\dto\ChasierDTO;
use EuroMillions\web\vo\dto\order_payment_provider\OrderPaymentProviderDTO;

class CashierIframeBuilder implements ICashierDTOBuilder
{
    private $paymentMethod;

    private $orderData;

    public function __construct(IHandlerPaymentGateway $paymentMethod, OrderPaymentProviderDTO $orderData)
    {
        $this->paymentMethod = $paymentMethod;
        $this->orderData = $orderData;
    }

    public function build()
    {
        $this->orderData->exChangeObject();
        $response = $this->paymentMethod->call(json_encode($this->orderData), $this->orderData->action(), 'post');
        return new ChasierDTO($this->paymentMethod->type(), json_decode($response, true), $this->orderData->getTransactionID());
    }
}
