<?php


namespace EuroMillions\web\components\transaction;


use EuroMillions\web\entities\AutomaticPurchaseTransaction;
use EuroMillions\web\interfaces\ITransactionGeneratorStrategy;

class AutomaticPurchaseGenerator implements ITransactionGeneratorStrategy
{

    public static function build(array $data)
    {
        try {
            $ticketPurchaseTransaction = new AutomaticPurchaseTransaction($data);
            $ticketPurchaseTransaction->toString();
        } catch( \Exception $e ) {
            throw new \Exception('Invalid argument to build entity');
        }
        return $ticketPurchaseTransaction;
    }
}