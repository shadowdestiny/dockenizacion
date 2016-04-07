<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\ITransactionData;

class FundsAddedTransaction extends Transaction implements ITransactionData
{

    protected $data;
    protected $hasFee;
    protected $amountAdded;


    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    public function toString()
    {
        // TODO: Implement toString() method.
    }

    public function fromString()
    {
        list($fee,$amount) = explode('#',$this->toString());
        $this->hasFee = $fee;
        $this->amountAdded = $amount;
    }
}