<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\ITransactionData;

class WinningsWithdrawTransaction extends Transaction implements ITransactionData
{
    protected $amountWithdrawed;
    protected $accountBankId;

    public function toString()
    {
        return $this->data;
    }

    public function fromString()
    {
        list($amountWithdrawed, $accountBankId) = explode('#',$this->data);
        $this->amountWithdrawed = $amountWithdrawed;
        $this->accountBankId = $accountBankId;
        return $this;
    }

}