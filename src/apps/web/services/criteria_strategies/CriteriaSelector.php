<?php


namespace EuroMillions\web\services\criteria_strategies;


use EuroMillions\shared\components\PaymentsCollection;
use EuroMillions\web\interfaces\IPaymentsCriteria;
use EuroMillions\web\vo\enum\PaymentSelectorType;

final class CriteriaSelector implements IPaymentsCriteria
{

    private $paymentSelectorType;

    public function __construct(PaymentSelectorType $paymentSelectorType)
    {
        $this->paymentSelectorType= $paymentSelectorType;
    }

    public function meetCriteria(PaymentsCollection $paymentsCollection)
    {
        $newPaymentsCollection = new PaymentsCollection();
        foreach($paymentsCollection->getIterator() as $k => $payment)
        {
            if($payment->get()->type()->value() == $this->paymentSelectorType->value())
            {
                $newPaymentsCollection->addItem($k,$payment);
            }
        }
        return $newPaymentsCollection;
    }
}