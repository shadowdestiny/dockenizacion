<?php


namespace EuroMillions\web\components\transaction;


use EuroMillions\web\entities\TicketPurchaseTransaction;
use EuroMillions\web\interfaces\ITransactionGeneratorStrategy;

class TicketPurchaseGenerator implements ITransactionGeneratorStrategy
{

    public static function build(array $data)
    {
        try {
            $ticketPurchaseTransaction = new TicketPurchaseTransaction($data);
            $ticketPurchaseTransaction->toString();
        } catch( \Exception $e ) {
            throw new \Exception('Invalid argument to build entity');
        }
        return $ticketPurchaseTransaction;
    }
}