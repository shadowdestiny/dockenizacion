<?php


namespace EuroMillions\web\components\transaction;


use EuroMillions\web\entities\SubscriptionPurchaseTransaction;
use EuroMillions\web\interfaces\ITransactionGeneratorStrategy;

class SubscriptionPurchaseGenerator implements ITransactionGeneratorStrategy
{

    public static function build(array $data)
    {
        try {
            $subscriptionPurchaseTransaction = new SubscriptionPurchaseTransaction($data);
            $subscriptionPurchaseTransaction->toString();
        } catch( \Exception $e ) {
            throw new \Exception('Invalid argument to build entity');
        }
        return $subscriptionPurchaseTransaction;
    }
}