<?php


namespace EuroMillions\web\components\transaction;


use EuroMillions\web\entities\WinningsWithdrawTransaction;
use EuroMillions\web\interfaces\ITransactionGeneratorStrategy;

class WinningsWithdrawGenerator implements ITransactionGeneratorStrategy
{

    public static function build(array $data)
    {
        $winningsWithdrawTransaction = new WinningsWithdrawTransaction();
        try {
            $winningsWithdrawTransaction->setAccountBankId($data['accountBankId']);
            $winningsWithdrawTransaction->setAmountWithdrawed($data['amountWithdrawed']);
            $winningsWithdrawTransaction->setState($data['state']);
            $winningsWithdrawTransaction->setTransactionID(!empty($data['transactionID']) ? $data['transactionID'] : 0);
            $winningsWithdrawTransaction->setWalletBefore($data['walletBefore']);
            $winningsWithdrawTransaction->setWalletAfter($data['walletAfter']);
            $winningsWithdrawTransaction->setDate($data['now']);
            $winningsWithdrawTransaction->setUser($data['user']);
            $winningsWithdrawTransaction->setStatus(!empty($data['status']) ? $data['status'] : '');
            $winningsWithdrawTransaction->setLotteryName(!empty($data['lotteryName']) ? $data['lotteryName'] : '');
            $winningsWithdrawTransaction->toString();
        } catch( \Exception $e ) {
            throw new \Exception('Invalid argument to build entity');
        }
        return $winningsWithdrawTransaction;

    }
}