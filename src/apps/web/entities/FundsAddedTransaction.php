<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\ITransactionData;

class FundsAddedTransaction extends Transaction implements ITransactionData
{

    protected $hasFee;
    protected $amountAdded;


    public function toString()
    {
        return $this->data;
    }

    public function fromString()
    {
        list($fee,$amount) = explode('#',$this->toString());
        $this->hasFee = $fee;
        $this->amountAdded = $amount;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getHasFee()
    {
        return $this->hasFee;
    }

    /**
     * @param mixed $hasFee
     */
    public function setHasFee($hasFee)
    {
        $this->hasFee = $hasFee;
    }

    /**
     * @return mixed
     */
    public function getAmountAdded()
    {
        return $this->amountAdded;
    }

    /**
     * @param mixed $amountAdded
     */
    public function setAmountAdded($amountAdded)
    {
        $this->amountAdded = $amountAdded;
    }

}