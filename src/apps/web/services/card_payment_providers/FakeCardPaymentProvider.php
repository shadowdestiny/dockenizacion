<?php
namespace EuroMillions\web\services\card_payment_providers;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\CreditCard;
use Money\Money;

class FakeCardPaymentProvider implements ICardPaymentProvider
{

    public function charge(Money $amount, CreditCard $card)
    {
        $result = $card->getLastNumbersOfCreditCard() % 2 == 0;
        return new ActionResult($result);
    }
}