<?php


namespace EuroMillions\web\components;


use EuroMillions\web\components\cashier_dto_builder\CashierFormBuilder;
use EuroMillions\web\components\cashier_dto_builder\CashierIframeBuilder;
use EuroMillions\web\components\cashier_dto_builder\CashierOrderProcessingBuilder;
use EuroMillions\web\interfaces\IHandlerPaymentGateway;
use EuroMillions\web\vo\dto\OrderPaymentProviderDTO;
use EuroMillions\web\vo\enum\PaymentSelectorType;

class CashierBuilderContext
{

    private $cashierDTO;

    public function __construct(IHandlerPaymentGateway $paymentMethod, OrderPaymentProviderDTO $orderData, $hasOrder)
    {
        if($hasOrder)
        {
            $this->cashierDTO= new CashierOrderProcessingBuilder($paymentMethod);
        }
        if($paymentMethod->type() == PaymentSelectorType::CREDIT_CARD_METHOD)
        {
            $this->cashierDTO= new CashierFormBuilder($paymentMethod,$orderData);
        }
        if($paymentMethod->type() == PaymentSelectorType::OTHER_METHOD)
        {
            $this->cashierDTO= new CashierIframeBuilder($paymentMethod,$orderData);
        }
    }


    public function builder()
    {
        return $this->cashierDTO;
    }


}