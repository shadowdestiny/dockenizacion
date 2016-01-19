<?php


namespace EuroMillions\web\services;


use EuroMillions\web\interfaces\ICardPaymentProvider;
use Money\Money;

class PaymentProviderService
{

    public function __construct()
    {

    }

    public function charge(ICardPaymentProvider $paymentMethod, Money $amount)
    {
        $isOk = true;
        $provider = $this->getProvider($paymentMethod);
    }
}