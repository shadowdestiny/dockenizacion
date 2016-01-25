<?php
namespace EuroMillions\web\services\card_payment_providers\payxpert;

class GatewayClientWrapper
{
    private $gateway;

    public function __construct(PayXpertConfig $config)
    {
        include_once 'GatewayClient.php';
        $this->gateway = new \GatewayClient(PayXpertConfig::URL, $config->getOriginatorId(), $config->getApiKey());
    }

    public function newTransaction($type)
    {
        return new GatewayTransactionWrapper($this->gateway->newTransaction($type));
    }
}