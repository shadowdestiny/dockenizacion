<?php


namespace EuroMillions\services;


use EuroMillions\entities\PaymentMethod;
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