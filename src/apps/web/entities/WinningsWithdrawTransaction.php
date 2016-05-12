<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\ITransactionData;

class WinningsWithdrawTransaction extends Transaction implements ITransactionData
{
    protected $amountWithdrawed;
    protected $accountBankId;
    protected $state;

    /**
     * @return mixed
     */
    public function getAmountWithdrawed()
    {
        return $this->amountWithdrawed;
    }

    /**
     * @param mixed $amountWithdrawed
     */
    public function setAmountWithdrawed($amountWithdrawed)
    {
        $this->amountWithdrawed = $amountWithdrawed;
    }

    /**
     * @return mixed
     */
    public function getAccountBankId()
    {
        return $this->accountBankId;
    }

    /**
     * @param mixed $accountBankId
     */
    public function setAccountBankId($accountBankId)
    {
        $this->accountBankId = $accountBankId;
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

    public function toString()
    {
        $this->data = $this->accountBankId.'#'.$this->amountWithdrawed.'#'.$this->state;
    }

    public function fromString()
    {
        list($accountBankId, $amountWithdrawed, $state) = explode('#',$this->data);
        $this->amountWithdrawed = $amountWithdrawed;
        $this->accountBankId = $accountBankId;
        $this->state = $state;
        return $this;
    }

}