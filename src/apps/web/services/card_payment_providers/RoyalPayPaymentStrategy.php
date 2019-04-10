<?php


namespace EuroMillions\web\services\card_payment_providers;


use EuroMillions\web\services\card_payment_providers\royalpay\RoyalPayConfig;


class RoyalPayPaymentStrategy implements ICreditCardStrategy
{

    private $config;

    public function __construct($config = null)
    {
        $this->config = $config;
    }

    public function get()
    {
        return new RoyalPayPaymentProvider(
            new RoyalPayConfig(
                $this->config['endpoint'],
                $this->config['api_key'],
                $this->config['weight'],
                $this->config['countries']
            )
        );
    }


}