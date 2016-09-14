<?php


namespace EuroMillions\web\services\card_payment_providers;


use EuroMillions\web\services\card_payment_providers\widecard\WideCardConfig;


class WideCardPaymentStrategy implements ICreditCardStrategy
{

    private $config;

    public function __construct($config = null)
    {
        $this->config = $config;
    }

    public function get()
    {
        return new WideCardPaymentProvider(new WideCardConfig($this->config['endpoint']));
    }


}