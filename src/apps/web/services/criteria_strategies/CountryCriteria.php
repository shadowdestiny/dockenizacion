<?php


namespace EuroMillions\web\services\criteria_strategies;


use EuroMillions\shared\components\PaymentsCollection;
use EuroMillions\web\interfaces\IPaymentsCriteria;
use EuroMillions\web\vo\PaymentCountry;

class CountryCriteria implements IPaymentsCriteria
{


    protected $paymentCountry;

    public function __construct(PaymentCountry $paymentCountry)
    {
        $this->paymentCountry= $paymentCountry;
    }


    public function meetCriteria(PaymentsCollection $paymentsCollection)
    {
        $newPaymentsCollection = new PaymentsCollection();
        foreach($paymentsCollection->getIterator() as $k => $payment)
        {
            if($payment->get()->getPaymentCountry() == $this->paymentCountry->toNative())
            {
                $newPaymentsCollection->addItem($k,$payment);
            }
        }
        return $newPaymentsCollection;

    }
}