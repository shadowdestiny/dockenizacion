<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\ITransactionData;

class ManualDepositTransaction extends PurchaseTransaction implements ITransactionData
{
    protected $hasFee;
    protected $amountAdded;

    public function __construct(array $data)
    {
        $this->setLotteryId($data['lottery_id']);
        $this->setNumBets($data['numBets']);
        $this->setHasFee($data['feeApplied']);
        $this->setWalletBefore($data['walletBefore']);
        $this->setWalletAfter($data['walletAfter']);
        $this->setAmountAdded($data['amount']);
        $this->setTransactionID($data['transactionID']);
        $this->setDate($data['now']);
        $this->setUser($data['user']);
    }

    public function toString()
    {
        $this->data = $this->getHasFee().'#'.$this->getAmountAdded();
    }

    public function fromString()
    {
        list($fee,$amount) = explode('#',$this->data);
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

    public function getEntityType()
    {
        return parent::DEPOSIT_TRANSACTION_TYPE;
    }


}