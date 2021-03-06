<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\ITransactionData;

class RefundTransaction extends Transaction implements ITransactionData
{

    protected $amountRefunded;

    public function toString()
    {
        $this->data = $this->amountRefunded;
    }

    public function fromString()
    {
        $this->amountRefunded = $this->data;
        return $this;
    }

}