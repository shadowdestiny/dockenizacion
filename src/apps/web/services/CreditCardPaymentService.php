<?php
namespace apps\web\services;

use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use Money\Money;

class CreditCardPaymentService
{
    private $provider;
    public function __construct(ICardPaymentProvider $provider)
    {
        $this->provider = $provider;
    }

    public function charge(Money $amount, User $user, $concept)
    {
        $this->provider->charge(//EMTD VOY POR AQUÍ!!!!!!!!!!!!);
    }
}