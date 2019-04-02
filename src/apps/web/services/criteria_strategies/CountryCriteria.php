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
        foreach($paymentsCollection->getIteratorSortByWeight(
            function($itemA,$itemB){
                return $itemA->get()->getPaymentWeight()->getValue() < $itemB->get()->getPaymentWeight()->getValue();
            }
        ) as $k => $payment)
        {
            if(array_intersect($payment->get()->getPaymentCountry()->countries(),$this->paymentCountry->countries()))
            {
                $newPaymentsCollection->addItem($k,$payment);
            }
        }
        return $newPaymentsCollection;
    }
}