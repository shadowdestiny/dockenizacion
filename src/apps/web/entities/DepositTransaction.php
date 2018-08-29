<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\ITransactionData;

class DepositTransaction extends PurchaseTransaction implements ITransactionData
{

    protected $hasFee;
    protected $amountAdded;
    protected $status;
    protected $lotteryName;
    protected $withWallet;


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
        $this->setLotteryName($data['lotteryName']);
        $this->setStatus(!empty($data['status']) ? $data['status'] : 'SUCCESS');
        $this->setWithWallet($data['withWallet']);

    }

    public function toString()
    {
        $this->data = $this->getHasFee().'#'.$this->getAmountAdded().'#'.$this->getStatus().'#'.$this->getLotteryId().'#'.$this->getLotteryName().'#'.$this->withWallet;
    }

    public function fromString()
    {
        @list($fee,$amount,$status,$lotteryID,$lotteryName,$withWallet) = explode('#',$this->data);
        $this->hasFee = $fee;
        $this->amountAdded = $amount;
        $this->status = $status;
        $this->lotteryId = $lotteryID;
        $this->lotteryName = $lotteryName;
        $this->withWallet = $withWallet;
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

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getLotteryName()
    {
        return $this->lotteryName;
    }

    /**
     * @param mixed $lotteryName
     */
    public function setLotteryName($lotteryName)
    {
        $this->lotteryName = $lotteryName;
    }


    /**
     * @return mixed
     */
    public function getWithWallet()
    {
        return $this->withWallet;
    }

    /**
     * @param mixed $withWallet
     */
    public function setWithWallet($withWallet)
    {
        $this->withWallet = $withWallet;
    }



}