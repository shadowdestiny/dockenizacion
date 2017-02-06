<?php


namespace EuroMillions\web\components\transaction;


use EuroMillions\web\entities\ManualDepositTransaction;
use EuroMillions\web\interfaces\ITransactionGeneratorStrategy;

class ManualDepositGenerator implements ITransactionGeneratorStrategy
{

    public static function build(array $data)
    {
        try {
            $manualDepositTransaction = new ManualDepositTransaction($data);
            $manualDepositTransaction->toString();
        } catch( \Exception $e ) {
            throw new \Exception('Invalid argument to build entity');
        }
        return $manualDepositTransaction;
    }
}