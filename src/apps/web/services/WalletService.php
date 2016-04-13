<?php
namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\shared\interfaces\IResult;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\CreditCardCharge;
use EuroMillions\web\vo\dto\WalletDTO;

class WalletService
{
    private $entityManager;
    private $currencyConversionService;

    public function __construct(EntityManager $entityManager, CurrencyConversionService $currencyConversionService)
    {
        $this->entityManager = $entityManager;
        $this->currencyConversionService = $currencyConversionService;
    }

    /**
     * @param ICardPaymentProvider $provider
     * @param CreditCard $card
     * @param User $user
     * @param CreditCardCharge $creditCardCharge
     * @return IResult
     */
    public function rechargeWithCreditCard(ICardPaymentProvider $provider,
                                           CreditCard $card,
                                           User $user,
                                           CreditCardCharge $creditCardCharge)
    {
        $amount = $creditCardCharge->getFinalAmount();
        $payment_result = $provider->charge($amount, $card);
        if ($payment_result->success()) {
            $user->reChargeWallet($creditCardCharge->getNetAmount());
            try {
                $this->entityManager->flush($user);
            } catch (\Exception $e) {
                //EMTD Log and warn the admin
            }
        }
        return $payment_result;
    }

    public function getWalletDTO( User $user )
    {
        if( null != $user ) {
            try {
                $wallet = $user->getWallet();
                $uploaded_convert = $this->currencyConversionService->convert($wallet->getUploaded(), $user->getUserCurrency());
                $amount_uploaded = $this->currencyConversionService->toString($uploaded_convert, $user->getLocale());
                $current_winnnings_convert = $this->currencyConversionService->convert($user->getWinningAbove(), $user->getUserCurrency());
                $amount_current_winning = $this->currencyConversionService->toString($current_winnnings_convert, $user->getLocale());
                $balance_convert = $this->currencyConversionService->convert($wallet->getBalance(), $user->getUserCurrency());
                $amount_balance = $this->currencyConversionService->toString($balance_convert, $user->getLocale());
                $winnings_convert = $this->currencyConversionService->convert($wallet->getWinnings(), $user->getUserCurrency());
                $amount_winnings = $this->currencyConversionService->toString($winnings_convert, $user->getLocale());
                return new WalletDTO( $amount_balance, $amount_uploaded, $amount_winnings, $amount_current_winning);
            } catch ( \Exception $e ) {
                return null;
            }
        }
        return null;
    }

    public function logMovement()
    {
        //EMTD TO DO
    }
}