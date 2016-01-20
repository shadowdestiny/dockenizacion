<?php
namespace EuroMillions\web\interfaces;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\shared\vo\results\PaymentProviderResult;
use Money\Money;

interface ICardPaymentProvider
{
    /**
     * @param Money $amount
     * @param CreditCard $card
     * @return PaymentProviderResult
     */
    public function charge(Money $amount, CreditCard $card);
}