<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\ITransactionData;

class WinningsWithdrawTransaction extends Transaction implements ITransactionData
{

    protected $data;
    protected $amountWithdrawed;
    protected $accountBankId;

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }


    public function toString()
    {
        // TODO: Implement toString() method.
    }

    public function fromString()
    {
        list($amountWithdrawed, $accountBankId) = explode('#',$this->data);
        $this->amountWithdrawed = $amountWithdrawed;
        $this->accountBankId = $accountBankId;
        return $this;
    }
}