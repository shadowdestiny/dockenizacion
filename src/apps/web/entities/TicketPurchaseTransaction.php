<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\exceptions\BadEntityInitializationException;
use EuroMillions\web\interfaces\ITransactionData;

class TicketPurchaseTransaction extends PurchaseTransaction implements ITransactionData
{

    protected $amountWithWallet;
    protected $amountWithCreditCard;
    protected $feeApplied;


    public function __construct(array $data)
    {
        $this->setLotteryId($data['lottery_id']);
        $this->setNumBets($data['numBets']);
        $this->setAmountWithWallet($data['amountWithWallet']);
        $this->setAmountWithCreditCard($data['amountWithCreditCard']);
        $this->setFeeApplied($data['feeApplied']);
        $this->setWalletBefore($data['walletBefore']);
        $this->setWalletAfter($data['walletAfter']);
        $this->setDate($data['now']);
        $this->setUser($data['user']);
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

    public function toString()
    {
        $this->data = $this->lotteryId.'#'.$this->numBets.'#'.$this->amountWithWallet.'#'.$this->amountWithCreditCard.'#'.$this->feeApplied;
    }

    public function fromString()
    {
        try {

            list($lotteryId,
                $numBets,
                $amountWithWallet,
                $amountWithCreditCard,
                $feeApplied) = explode('#',$this->data);

            $this->lotteryId = $lotteryId;
            $this->numBets = $numBets;
            $this->amountWithWallet = $amountWithWallet;
            $this->amountWithCreditCard = $amountWithCreditCard;
            $this->feeApplied = $feeApplied;

        } catch ( \Exception $e ) {
            throw new BadEntityInitializationException('Invalid data format');
        }
        return $this;
    }
}