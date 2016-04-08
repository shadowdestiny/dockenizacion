<?php


namespace EuroMillions\web\entities;


class PurchaseTransaction extends Transaction
{

    protected $lotteryId;
    protected $numBets;

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

}