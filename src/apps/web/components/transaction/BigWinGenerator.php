<?php


namespace EuroMillions\web\components\transaction;


use EuroMillions\web\entities\BigWinTransaction;
use EuroMillions\web\interfaces\ITransactionGeneratorStrategy;

class BigWinGenerator implements ITransactionGeneratorStrategy
{

    public static function build(array $data)
    {
        try {
            $bigWinTransaction = new BigWinTransaction($data);
            $bigWinTransaction->toString();
        } catch( \Exception $e ) {
            throw new \Exception('Invalid argument to build entity');
        }
        return $bigWinTransaction;
    }
}