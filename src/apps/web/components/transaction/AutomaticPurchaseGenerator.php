<?php


namespace EuroMillions\web\components\transaction;


use EuroMillions\web\entities\AutomaticPurchaseTransaction;
use EuroMillions\web\interfaces\ITransactionGeneratorStrategy;

class AutomaticPurchaseGenerator implements ITransactionGeneratorStrategy
{

    public static function build(array $data)
    {
        $ticketPurchaseTransaction = new AutomaticPurchaseTransaction();
        try {
            $ticketPurchaseTransaction->setLotteryId($data['lottery_id']);
            $ticketPurchaseTransaction->setNumBets($data['numBets']);
            $ticketPurchaseTransaction->setWalletBefore($data['walletBefore']);
            $ticketPurchaseTransaction->setWalletAfter($data['walletAfter']);
            $ticketPurchaseTransaction->setDate($data['now']);
            $ticketPurchaseTransaction->setUser($data['user']);
            $ticketPurchaseTransaction->toString();
        } catch( \Exception $e ) {
            throw new \Exception('Invalid argument to build entity');
        }
        return $ticketPurchaseTransaction;
    }
}