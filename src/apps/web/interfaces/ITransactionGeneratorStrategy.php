<?php


namespace EuroMillions\web\interfaces;


interface ITransactionGeneratorStrategy
{
    public static function build( array $data );
}