<?php
namespace EuroMillions\web\services;

use EuroMillions\shared\interfaces\IResult;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\vo\CreditCard;
use Money\Money;

class WalletService
{
    /**
     * @param ICardPaymentProvider $provider
     * @param CreditCard $card
     * @param User $user
     * @param Money $amount
     * @returns IResult
     */
    public function rechargeWithCreditCard(ICardPaymentProvider $provider, CreditCard $card, User $user, Money $amount)
    {
        $payment_result = $provider->charge($amount, $card);
        if ($payment_result->success()) {
            $user->reChargeWallet($amount);
        }
        return $payment_result;
    }
}