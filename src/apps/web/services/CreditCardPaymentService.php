<?php
namespace apps\web\services;

use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\vo\CreditCard;
use Money\Money;

class CreditCardPaymentService
{
    private $provider;
    public function __construct(ICardPaymentProvider $provider )
    {
        $this->provider = $provider;
    }

    public function charge(Money $amount, CreditCard $card, $concept)
    {
        //EMTD missing implementation: store the transaction with the concept, etc
        return $this->provider->charge($amount, $card);
    }
}