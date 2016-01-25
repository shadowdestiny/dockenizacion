<?php


namespace EuroMillions\web\services\card_payment_providers\payxpert;


class GatewayTransactionWrapper
{
    private $transaction;
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    public function setTransactionInformation()
    {
        return $this->transaction->setTransactionInformation(...func_get_args());
    }

    public function setCardInformation()
    {
        return $this->transaction->setCardInformation(...func_get_args());
    }

    public function setShopperInformation()
    {
        return $this->transaction->setShopperInformation(...func_get_args());
    }

    public function send()
    {
        return $this->transaction->send();
    }

}