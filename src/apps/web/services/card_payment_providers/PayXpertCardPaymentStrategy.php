<?php


namespace EuroMillions\web\services\card_payment_providers;

use EuroMillions\web\services\card_payment_providers\payxpert\PayXpertConfig;

class PayXpertCardPaymentStrategy implements ICreditCardStrategy
{

    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function get()
    {
        return new PayXpertCardPaymentProvider($this->getPayXpertConfig());
    }

    private function getPayXpertConfig()
    {
        return new PayXpertConfig($this->config['originator_id'], $this->config['originator_name'], $this->config['api_key']);
    }
}