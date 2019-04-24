<?php


namespace EuroMillions\tests\helpers\mothers;

use EuroMillions\shared\components\builder\PaymentProviderDTOBuilder;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\dto\UserDTO;
use Money\Currency;
use Money\Money;

class PaymentProviderMother
{

    public static function aPaymentProvider($provider = null, Money $amount = null, CreditCard $card = null)
    {
        $amount = $amount ?: new Money(10000, new Currency('EUR'));
        $card = $card ?: CreditCardMother::aValidCreditCard();
        $user = UserMother::aJustRegisteredUser()->build();
        $order = OrderMother::aJustOrder()->buildANewWay();

        $paymentProviderDTO = (new PaymentProviderDTOBuilder())
            ->setProvider($provider)
            ->setUser(new UserDTO($user))
            ->setOrder($order)
            ->setAmount($amount)
            ->setCard($card);

        return $paymentProviderDTO->build();
    }
}