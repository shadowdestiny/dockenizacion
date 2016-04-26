<?php


namespace EuroMillions\web\components\transaction;


use EuroMillions\web\entities\ManualAdjustmentTransaction;
use EuroMillions\web\interfaces\ITransactionGeneratorStrategy;

class ManualAdjustmentGenerator implements ITransactionGeneratorStrategy
{

    public static function build(array $data)
    {
        try {
            $manualAdjustmentTransaction = new ManualAdjustmentTransaction($data);
            $manualAdjustmentTransaction->toString();
        } catch( \Exception $e ) {
            throw new \Exception('Invalid argument to build entity');
        }
        return $manualAdjustmentTransaction;
    }
}