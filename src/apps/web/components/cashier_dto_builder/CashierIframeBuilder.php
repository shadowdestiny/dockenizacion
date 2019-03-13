<?php


namespace EuroMillions\web\components\cashier_dto_builder;


use EuroMillions\web\interfaces\ICashierDTOBuilder;
use EuroMillions\web\interfaces\IHandlerPaymentGateway;
use EuroMillions\web\vo\dto\ChasierDTO;
use EuroMillions\web\vo\dto\OrderPaymentProviderDTO;
use EuroMillions\web\vo\TransactionId;

class CashierIframeBuilder implements ICashierDTOBuilder
{


    private $paymentMethod;

    private $orderData;

    public function __construct(IHandlerPaymentGateway $paymentMethod, OrderPaymentProviderDTO $orderData)
    {
        $this->paymentMethod= $paymentMethod;
        $this->orderData= $orderData;
    }

    public function build()
    {
        $this->orderData->exChangeObject();
        $response = $this->paymentMethod->call($this->orderData->toJson(),$this->orderData->action(),'post');
        return new ChasierDTO(json_decode($response, true),$this->orderData->getTransactionID(),"",$this->paymentMethod->type());
    }
}