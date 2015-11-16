<?php
namespace EuroMillions\web\interfaces;
use EuroMillions\web\vo\CreditCard;
use Money\Money;

interface ICardPaymentProvider
{
    public function charge(Money $amount, CreditCard $card);
}