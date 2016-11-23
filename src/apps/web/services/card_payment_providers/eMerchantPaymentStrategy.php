<?php

namespace EuroMillions\web\services\card_payment_providers;

use EuroMillions\web\services\card_payment_providers\emerchant\eMerchantConfig;

class eMerchantPaymentStrategy implements ICreditCardStrategy
{

    private $config;

    public function __construct($config = null)
    {
        $this->config = $config;
    }

    public function get()
    {
        return new eMerchantPaymentProvider(new eMerchantConfig($this->config['endpoint'], $this->config['api_key']));
    }


}