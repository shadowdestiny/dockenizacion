<?php


namespace EuroMillions\web\components\transaction;


use EuroMillions\web\entities\TicketPurchaseTransaction;
use EuroMillions\web\interfaces\ITransactionGeneratorStrategy;

class TicketPurchaseGenerator implements ITransactionGeneratorStrategy
{

    public static function build(array $data)
    {
        $ticketPurchaseTransaction = new TicketPurchaseTransaction();
        try {
            $ticketPurchaseTransaction->setLotteryId($data['lottery_id']);
            $ticketPurchaseTransaction->setNumBets($data['numBets']);
            $ticketPurchaseTransaction->setAmountWithWallet($data['amountWithWallet']);
            $ticketPurchaseTransaction->setAmountWithCreditCard($data['amountWithCreditCard']);
            $ticketPurchaseTransaction->setFeeApplied($data['feeApplied']);
            $ticketPurchaseTransaction->toString();
        } catch( \Exception $e ) {
            throw new \Exception('Invalid argument to build entity');
        }
        return $ticketPurchaseTransaction;
    }
}