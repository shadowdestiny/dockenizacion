<?php


namespace EuroMillions\web\components\cashier_dto_builder;


use EuroMillions\web\interfaces\ICashierDTOBuilder;
use EuroMillions\web\interfaces\IHandlerPaymentGateway;
use EuroMillions\web\vo\dto\ChasierDTO;
use EuroMillions\web\vo\dto\OrderPaymentProviderDTO;

final class CashierFormBuilder implements ICashierDTOBuilder
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
        return new ChasierDTO(null,$this->transactionID,"",$this->paymentMethod->type());
    }
}