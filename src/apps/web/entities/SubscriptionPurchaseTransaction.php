<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\exceptions\BadEntityInitializationException;
use EuroMillions\web\interfaces\ITransactionData;

class SubscriptionPurchaseTransaction extends PurchaseTransaction implements ITransactionData
{

    protected $amountWithWallet;
    protected $amountWithCreditCard;
    protected $feeApplied;
    protected $discount;


    public function __construct(array $data)
    {
        $this->setLotteryId($data['lottery_id']);
        $this->setNumBets($data['numBets']);
        $this->setAmountWithWallet($data['amountWithWallet']);
        $this->setAmountWithCreditCard($data['amountWithCreditCard']);
        $this->setFeeApplied($data['feeApplied']);
        $this->setWalletBefore($data['walletBefore']);
        $this->setWalletAfter($data['walletAfter']);
        $this->setTransactionID($data['transactionID']);
        $this->setDate($data['now']);
        $this->setUser($data['user']);
        $this->setPlayConfigs($data['playConfigs']);
        $this->setDiscount($data['discount']);
    }

    /**
     * @return mixed
     */
    public function getAmountWithWallet()
    {
        return $this->amountWithWallet;
    }

    /**
     * @param mixed $amountWithWallet
     */
    public function setAmountWithWallet($amountWithWallet)
    {
        $this->amountWithWallet = $amountWithWallet;
    }

    /**
     * @return mixed
     */
    public function getAmountWithCreditCard()
    {
        return $this->amountWithCreditCard;
    }

    /**
     * @param mixed $amountWithCreditCard
     */
    public function setAmountWithCreditCard($amountWithCreditCard)
    {
        $this->amountWithCreditCard = $amountWithCreditCard;
    }

    /**
     * @return mixed
     */
    public function getFeeApplied()
    {
        return $this->feeApplied;
    }

    /**
     * @param mixed $feeApplied
     */
    public function setFeeApplied($feeApplied)
    {
        $this->feeApplied = $feeApplied;
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
        $this->data = $this->lotteryId.'#'.$this->numBets.'#'.$this->amountWithWallet.'#'.$this->amountWithCreditCard.'#'.$this->feeApplied.'#'.implode(',',$this->playConfigs).'#'.$this->discount;
    }

    public function fromString()
    {
        try {
            if (count(explode('#',$this->data)) <= 6) {
                list($lotteryId,
                    $numBets,
                    $amountWithWallet,
                    $amountWithCreditCard,
                    $feeApplied,$playConfigs) = explode('#',$this->data);
                $discount = 0;
            } else {
                list($lotteryId,
                    $numBets,
                    $amountWithWallet,
                    $amountWithCreditCard,
                    $feeApplied,$playConfigs,$discount) = explode('#',$this->data);
            }

            $this->lotteryId = $lotteryId;
            $this->numBets = $numBets;
            $this->amountWithWallet = $amountWithWallet;
            $this->amountWithCreditCard = $amountWithCreditCard;
            $this->feeApplied = $feeApplied;
            $this->playConfigs = explode(',',$playConfigs);
            $this->discount = $discount;
        } catch ( \Exception $e ) {
            throw new BadEntityInitializationException('Invalid data format');
        }

        return $this;
    }

    public function getEntityType()
    {
        return parent::SUBSCRIPTION_PURCHASE_TYPE;
    }
}