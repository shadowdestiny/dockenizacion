<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\ITransactionData;

class RefundTransaction extends Transaction implements ITransactionData
{

    protected $data;
    protected $amountRefunded;

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
        $this->amountRefunded = $this->data;
        return $this;
    }
}