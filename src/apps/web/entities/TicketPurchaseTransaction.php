<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\ITransactionData;

class TicketPurchaseTransaction extends Transaction implements ITransactionData
{

    protected $data;
    protected $lotteryId;
    protected $numBets;
    protected $payWithWallet;


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
        list($lotteryId,$numBets,$payWithWallet) = explode('#',$this->toString());
        $this->lotteryId = $lotteryId;
        $this->numBets = $numBets;
        $this->payWithWallet = $payWithWallet;
    }
}