<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\ITransactionData;

class AutomaticPurchaseTransaction extends Transaction implements ITransactionData
{
    protected $data;
    protected $lotteryId;
    protected $numBets;

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
        return $this->data;
    }

    public function fromString()
    {
        list($lotteryId, $numBets) = explode('#', $this->data);
        $this->lotteryId = $lotteryId;
        $this->numBets = $numBets;
        return $this;
    }
}