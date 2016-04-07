<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\ITransactionData;

class TicketPurchaseTransaction extends Transaction implements ITransactionData
{

    protected $data;
    protected $lotteryId;
    protected $numBets;
    protected $amountWithWallet;
    protected $amountWithCreditCard;
    protected $feeApplied;


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
        list($lotteryId,
             $numBets,
             $amountWithWallet,
             $amountWithCreditCard,
             $feeApplied) = explode('#',$this->toString());

        $this->lotteryId = $lotteryId;
        $this->numBets = $numBets;
        $this->amountWithWallet = $amountWithWallet;
        $this->amountWithCreditCard = $amountWithCreditCard;
        $this->feeApplied = $feeApplied;
        return $this;
    }
}