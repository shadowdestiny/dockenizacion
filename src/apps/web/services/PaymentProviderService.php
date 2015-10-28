<?php


namespace EuroMillions\web\services;


use EuroMillions\web\entities\PaymentMethod;
use Money\Money;

class PaymentProviderService
{

    public function __construct()
    {

    }

    public function charge(PaymentMethod $paymentMethod, Money $amount)
    {
        $isOk = true;
        $provider = $this->getProvider($paymentMethod);

        //call provider callPayment();
    }

    private function getProvider(PaymentMethod $paymentMethod)
    {

    }
}