<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 2/08/18
 * Time: 11:04
 */

namespace EuroMillions\web\services\card_payment_providers;


use EuroMillions\shared\vo\results\PaymentProviderResult;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\vo\CreditCard;
use Money\Money;

class MoneyMatrixPaymentProvider implements ICardPaymentProvider
{

    /**
     * @param Money $amount
     * @param CreditCard $card
     * @return PaymentProviderResult
     */
    public function charge(Money $amount, CreditCard $card)
    {

    }

    /**
     * @param User $user
     * @return mixed
     */
    public function user(User $user)
    {

    }
}