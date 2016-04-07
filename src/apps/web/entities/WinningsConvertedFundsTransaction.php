<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\ITransactionData;

class WinningsConvertedFundsTransaction extends Transaction implements ITransactionData
{

    protected $data;
    protected $amountConverted;


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

    }

    public function fromString()
    {
        $this->amountConverted = $this->data;
        return $this;
    }
}