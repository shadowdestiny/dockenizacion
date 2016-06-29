<?php


namespace EuroMillions\web\services\card_payment_providers;


use EuroMillions\web\services\card_payment_providers\gcp\GCPConfig;

class GCPCardPaymentStrategy implements ICreditCardStrategy
{

    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function get()
    {
        return new GCPCardPaymentProvider($this->getGCPConfig());
    }

    private function getGCPConfig()
    {
        return new GCPConfig($this->config['url'],
                             $this->config['mid'],
                             $this->config['visa_gw_id'],
                             $this->config['master_gw_id'],
                             $this->config['visa_sign_key'],
                             $this->config['master_sign_key']
        );
    }
}