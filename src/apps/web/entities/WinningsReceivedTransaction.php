<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\ITransactionData;

class WinningsReceivedTransaction extends Transaction implements ITransactionData
{

    protected $drawId;
    protected $betId;
    protected $amount;
    protected $state;
    protected $lotteryId;

    public function __construct(array $data)
    {
        $this->setDrawId($data['draw_id']);
        $this->setBetId($data['bet_id']);
        $this->setAmount($data['amount']);
        $this->setState($data['state']);
        $this->setWalletBefore($data['walletBefore']);
        $this->setWalletAfter($data['walletAfter']);
        $this->setDate($data['now']);
        $this->setUser($data['user']);
        $this->setLotteryId($data['lottery_id']);
    }

    public function toString()
    {
        $this->data = $this->drawId.'#'.$this->betId.'#'.$this->amount.'#'.$this->state.'#'.$this->lotteryId;
    }

    public function fromString()
    {
        list($drawId, $betId, $amount,$state,$lotteryId) = explode('#',$this->data);
        $this->drawId = $drawId;
        $this->betId = $betId;
        $this->amount = $amount;
        $this->state = $state;
        $this->lotteryId = isset($lotteryId) ? $lotteryId : 1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDrawId()
    {
        return $this->drawId;
    }

    /**
     * @param mixed $drawId
     */
    public function setDrawId($drawId)
    {
        $this->drawId = $drawId;
    }

    /**
     * @return mixed
     */
    public function getBetId()
    {
        return $this->betId;
    }

    /**
     * @param mixed $betId
     */
    public function setBetId($betId)
    {
        $this->betId = $betId;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    public function getEntityType()
    {
        return parent::WINNING_RECEIVED_TYPE;
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

}