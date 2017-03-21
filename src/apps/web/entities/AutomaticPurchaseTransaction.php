<?php

namespace EuroMillions\web\entities;

use EuroMillions\web\interfaces\ITransactionData;

class AutomaticPurchaseTransaction extends PurchaseTransaction implements ITransactionData
{
    public function __construct(array $data)
    {
        $this->setLotteryId($data['lottery_id']);
        $this->setNumBets($data['numBets']);
        $this->setWalletBefore($data['walletBefore']);
        $this->setWalletAfter($data['walletAfter']);
        $this->setDate($data['now']);
        $this->setUser($data['user']);
        $this->setPlayConfigs($data['playConfigs']);
    }

    public function toString()
    {
        $this->data = $this->getLotteryId().'#'.$this->getNumBets().'#'.$this->playConfigs;
    }

    public function fromString()
    {
        list($lotteryId, $numBets) = explode('#', $this->data);
        $this->lotteryId = $lotteryId;
        $this->numBets = $numBets;
        return $this;
    }
}