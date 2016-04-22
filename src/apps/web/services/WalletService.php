<?php
namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\shared\interfaces\IResult;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\CreditCardCharge;
use EuroMillions\web\vo\dto\WalletDTO;
use EuroMillions\web\vo\enum\TransactionType;
use Money\Money;

class WalletService
{
    private $entityManager;
    private $currencyConversionService;
    private $transactionService;

    public function __construct(EntityManager $entityManager, CurrencyConversionService $currencyConversionService, TransactionService $transactionService)
    {
        $this->entityManager = $entityManager;
        $this->currencyConversionService = $currencyConversionService;
        $this->transactionService = $transactionService;
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
            $walletBefore = $user->getWallet();
            $user->reChargeWallet($creditCardCharge->getNetAmount());
            try {
                $this->entityManager->persist($user);
                $this->entityManager->flush($user);
                $dataTransaction = [
                    'lottery_id' => 1,
                    'numBets' => count($user->getPlayConfig()),
                    'feeApplied' => $creditCardCharge->getIsChargeFee(),
                    'amountWithWallet' => 0,
                    'amountWithCreditCard' => $creditCardCharge->getFinalAmount()->getAmount(),
                    'user' => $user,
                    'walletBefore' => $walletBefore,
                    'walletAfter' => $user->getWallet(),
                    'now' => new \DateTime()
                ];
                $this->transactionService->storeTransaction(TransactionType::FUNDS_ADDED,$dataTransaction);
            } catch (\Exception $e) {
                //EMTD Log and warn the admin
            }
        }
        return $payment_result;
    }



    public function payWithWallet(User $user, PlayConfig $playConfig, $transactionType, $data )
    {
        try {
            $walletBefore = $user->getWallet();
            $user->pay($playConfig->getLottery()->getSingleBetPrice());
            $this->entityManager->flush($user);
            $data['now'] = new \DateTime();
            $data['walletBefore'] = $walletBefore;
            $data['walletAfter'] = $user->getWallet();
            $data['user'] = $user;
            $this->transactionService->storeTransaction($transactionType,$data);
        } catch ( \Exception $e ) {
            //EMTD Log and warn the admin
        }
    }

    public function withDraw( User $user, Money $amount )
    {
        try{
            $walletBefore = $user->getWallet();
            $newWallet = $user->getWallet()->withdraw($amount);
            if(null == $newWallet) {
                throw new \Exception('You don\'t have enough winning amount to complete transaction');
            }
            $user->setWallet($newWallet);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $data = [];
            $data['now'] = new \DateTime();
            $data['walletBefore'] = $walletBefore;
            $data['walletAfter'] = $user->getWallet();
            $data['user'] = $user;
            $data['accountBankId'] = '1';
            $data['amountWithdrawed'] = $amount->getAmount();
            $data['state'] = 'pending';
            $this->transactionService->storeTransaction(TransactionType::WINNINGS_WITHDRAW, $data);
        } catch ( \Exception $e ) {
            return new ActionResult(false, $e->getMessage());
            //EMTD Log and warn the admin
        }
        return new ActionResult(true);
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
                $wallet_dto = new WalletDTO($amount_balance, $amount_uploaded, $amount_winnings, $amount_current_winning);
                $balance = $this->currencyConversionService->toString($wallet->getBalance(), $user->getLocale());
                $winnings = $this->currencyConversionService->toString($wallet->getWinnings(), $user->getLocale());
                $wallet_dto->setBalance($balance);
                $wallet_dto->setWinnings($winnings);
                $wallet_dto->hasEnoughWinningsBalance($wallet->getWinnings());
                return $wallet_dto;
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