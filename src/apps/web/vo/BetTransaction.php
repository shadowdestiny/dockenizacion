<?php
namespace EuroMillions\web\vo;

class BetTransaction
{
    protected $lottery_id;
    protected $num_bets;
    protected $data;

    public function __construct($entityType, $transactionString)
    {
        $this->transactionToString = $transactionString;
        $this->entityType = $entityType;
        list($lottery_id, $num_bets) = explode('#', $transactionString);
        $this->lottery_id = $lottery_id;
        $this->num_bets = $num_bets;
    }

    public function getData()
    {
        return $this->lottery_id.'#'.$this->num_bets;
        // TODO: Implement toString() method.
    }

    public function getLotteryId()
    {
        return $this->lottery_id;
    }

    public function getNumBets()
    {
        return $this->num_bets;
    }
}