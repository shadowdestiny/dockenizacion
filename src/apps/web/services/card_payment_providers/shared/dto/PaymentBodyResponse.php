<?php


namespace EuroMillions\web\services\card_payment_providers\shared\dto;


abstract class PaymentBodyResponse
{

    protected $status;

    protected $statusMessage;

    protected $message;

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getStatusMessage()
    {
        return $this->statusMessage;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }


}