<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\ITransactionData;

class AutomaticPurchaseTransaction extends PurchaseTransaction implements ITransactionData
{

    public function toString()
    {
        $this->data = $this->getLotteryId().'#'.$this->getNumBets();
    }

    public function fromString()
    {
        list($lotteryId, $numBets) = explode('#', $this->data);
        $this->lotteryId = $lotteryId;
        $this->numBets = $numBets;
        return $this;
    }

}