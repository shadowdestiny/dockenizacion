<?php
namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\shared\interfaces\IResult;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\vo\CreditCard;
use Money\Money;

class WalletService
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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
            try {
                $this->entityManager->flush($user);
            } catch (\Exception $e) {
                //EMTD Log and warn the admin
            }
        }
        return $payment_result;
    }

    public function logMovement()
    {
        //EMTD TO DO
    }
}