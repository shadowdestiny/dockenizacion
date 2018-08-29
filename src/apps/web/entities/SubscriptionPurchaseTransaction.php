<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\exceptions\BadEntityInitializationException;
use EuroMillions\web\interfaces\ITransactionData;

class SubscriptionPurchaseTransaction extends PurchaseTransaction implements ITransactionData
{

    protected $hasFee;
    protected $amountAdded;
    protected $discount;
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
        $this->setPlayConfigs($data['playConfigs']);
        $this->setDate($data['now']);
        $this->setUser($data['user']);
        $this->setPendingBalanceAmount($data['amountWithCreditCard'] + $data['amountWithWallet']);
        $this->setLotteryName($data['lotteryName']);
        $this->setStatus(!empty($data['status']) ? $data['status'] : 'SUCCESS');
        $this->setWithWallet($data['withWallet']);
    }


    /**
     * @return mixed
     */
    public function getLotteryId()
    {
        return $this->lotteryId;
    }

    /**
     * @param mixed $lotteryId
     */
    public function setLotteryId($lotteryId)
    {
        $this->lotteryId = $lotteryId;
    }

    /**
     * @return mixed
     */
    public function getNumBets()
    {
        return $this->numBets;
    }

    /**
     * @param mixed $numBets
     */
    public function setNumBets($numBets)
    {
        $this->numBets = $numBets;
    }

    /**
     * @return mixed
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    public function toString()
    {
        $this->data = $this->getLotteryId().'#'.$this->getHasFee().'#'.$this->getAmountAdded().'#'.$this->getDiscount().'#'.$this->getStatus().'#'.$this->getLotteryName(). '#'.$this->withWallet;
    }

    public function fromString()
    {
        list($lotteryId,$fee,$amount,$discount,$status,$lotteryName,$withWallet) = explode('#',$this->data);
        $this->lotteryId = $lotteryId;
        $this->hasFee = $fee;
        $this->amountAdded = $amount;
        $this->discount = $discount;
        $this->status = $status;
        $this->lotteryName = $lotteryName;
        $this->withWallet = $withWallet;
        return $this;
    }

    public function getEntityType()
    {
        return parent::SUBSCRIPTION_PURCHASE_TYPE;
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