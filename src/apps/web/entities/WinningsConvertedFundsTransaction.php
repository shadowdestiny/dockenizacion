<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\ITransactionData;

class WinningsConvertedFundsTransaction extends Transaction implements ITransactionData
{

    protected $amountConverted;


    public function toString()
    {
        return $this->data;
    }

    public function fromString()
    {
        $this->amountConverted = $this->data;
        return $this;
    }

}