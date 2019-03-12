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

    private $transactionID;

    public function __construct(IHandlerPaymentGateway $paymentMethod, OrderPaymentProviderDTO $orderData, $transactionID)
    {
        $this->paymentMethod= $paymentMethod;
        $this->orderData= $orderData;
        $this->transactionID= $transactionID;
    }

    public function build()
    {
        //EMTD @David please remove instanceof, when you will do the refactor transactionID in Order object
        $this->orderData->setTransactionID($this->transactionID instanceof  TransactionId ? $this->transactionID->id() : $this->transactionID);
        $this->orderData->exChangeObject();
        $response = $this->paymentMethod->call($this->orderData->toJson(),$this->orderData->action(),'post');
        return new ChasierDTO(json_decode($response, true),$this->transactionID,"",$this->paymentMethod->type());
    }
}