<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\ITransactionData;

class RefundTransaction extends Transaction implements ITransactionData
{

    protected $amountRefunded;

    public function toString()
    {
        return $this->data;
    }

    public function fromString()
    {
        $this->amountRefunded = $this->data;
        return $this;
    }

}