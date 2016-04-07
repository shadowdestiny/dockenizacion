<?php


namespace EuroMillions\web\interfaces;


interface ITransactionData
{
    /** @return string */
    public function toString();
    /** @return void */
    public function fromString();

}