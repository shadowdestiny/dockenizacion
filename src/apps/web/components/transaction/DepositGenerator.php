<?php


namespace EuroMillions\web\components\transaction;


use EuroMillions\web\entities\DepositTransaction;
use EuroMillions\web\interfaces\ITransactionGeneratorStrategy;

class DepositGenerator implements ITransactionGeneratorStrategy
{
    public static function build(array $data)
    {
        try {
            $depositTransaction = new DepositTransaction($data);
            $depositTransaction->toString();
        } catch( \Exception $e ) {
            throw new \Exception('Invalid argument to build entity');
        }
        return $depositTransaction;
    }
}