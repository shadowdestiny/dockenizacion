<?php


namespace EuroMillions\web\components\transaction;


use EuroMillions\web\entities\WinningsReceivedTransaction;
use EuroMillions\web\interfaces\ITransactionGeneratorStrategy;

class WinningsReceivedGenerator implements ITransactionGeneratorStrategy
{
    public static function build(array $data)
    {
        try {
            $winningReceivedTransaction = new WinningsReceivedTransaction($data);
            $winningReceivedTransaction->toString();
        } catch( \Exception $e ) {
            throw new \Exception('Invalid argument to build entity');
        }
        return $winningReceivedTransaction;
    }
}