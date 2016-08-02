<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\ITransactionData;

class BigWinTransaction extends Transaction implements ITransactionData
{

    protected $drawId;
    protected $betId;
    protected $amount;
    protected $state;


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
    }

    public function toString()
    {
        $this->data = $this->drawId.'#'.$this->betId.'#'.$this->amount.'#'.$this->state;
    }

    public function fromString()
    {
        list($drawId, $betId, $amount,$state) = explode('#',$this->data);
        $this->drawId = $drawId;
        $this->betId = $betId;
        $this->amount = $amount;
        $this->state = $state;
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

}