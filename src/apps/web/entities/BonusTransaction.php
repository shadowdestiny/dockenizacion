<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\ITransactionData;

class BonusTransaction extends Transaction implements ITransactionData
{
    /** @return string */
    public function toString()
    {
        return $this->data;
    }

    /** @return void */
    public function fromString()
    {
        // TODO: Implement fromString() method.
    }

}