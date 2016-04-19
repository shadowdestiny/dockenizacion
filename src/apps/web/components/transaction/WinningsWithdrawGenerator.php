<?php


namespace EuroMillions\web\components\transaction;


use EuroMillions\web\entities\WinningsWithdrawTransaction;
use EuroMillions\web\interfaces\ITransactionGeneratorStrategy;

class WinningsWithdraw implements ITransactionGeneratorStrategy
{

    public static function build(array $data)
    {
        $winningsWithdrawTransaction = new WinningsWithdrawTransaction();
        try {
            $winningsWithdrawTransaction->setAccountBankId($data['accountBankId']);
            $winningsWithdrawTransaction->setAmountWithdrawed($data['amountWithdrawed']);
            $winningsWithdrawTransaction->setState($data['state']);
            $winningsWithdrawTransaction->setWalletBefore($data['walletBefore']);
            $winningsWithdrawTransaction->setWalletAfter($data['walletAfter']);
            $winningsWithdrawTransaction->setDate($data['now']);
            $winningsWithdrawTransaction->setUser($data['user']);
            $winningsWithdrawTransaction->toString();
        } catch( \Exception $e ) {
            throw new \Exception('Invalid argument to build entity');
        }
        return $winningsWithdrawTransaction;

    }
}