<?php


namespace EuroMillions\web\services\card_payment_providers\moneymatrix;


class MoneyMatrixConfig
{

    private $endpoint;

    public function __construct($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @return mixed
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }
}