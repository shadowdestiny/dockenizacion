<?php


namespace EuroMillions\web\components\cashier_dto_builder;

use EuroMillions\web\interfaces\ICashierDTOBuilder;
use EuroMillions\web\interfaces\IHandlerPaymentGateway;
use EuroMillions\web\vo\dto\ChasierDTO;

final class CashierOrderProcessingBuilder implements ICashierDTOBuilder
{
    private $paymentMethod;

    public function __construct(IHandlerPaymentGateway $paymentMethod)
    {
        $this->paymentMethod= $paymentMethod;
    }

    public function build()
    {
        return new ChasierDTO($this->paymentMethod->type(), null, null, false, "Order processing...");
    }
}
