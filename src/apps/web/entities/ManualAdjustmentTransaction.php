<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\ITransactionData;


class ManualAdjustmentTransaction extends Transaction implements ITransactionData
{

    protected $reason;

    /**
     * @return mixed
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @param mixed $reason
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    /** @return string */
    public function toString()
    {
        $this->data = $this->reason;
    }

    /** @return void */
    public function fromString()
    {
        $this->reason = $this->data;
    }
}