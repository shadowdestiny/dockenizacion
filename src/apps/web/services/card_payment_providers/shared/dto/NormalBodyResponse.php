<?php


namespace EuroMillions\web\services\card_payment_providers\shared\dto;


class NormalBodyResponse extends PaymentBodyResponse
{

    protected $status;

    public function __construct($status)
    {
        $this->status = $status;
    }


    public function getStatus()
    {
        return $this->status;
    }

}