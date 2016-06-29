<?php


namespace EuroMillions\web\services\card_payment_providers;



use EuroMillions\web\components\tags\EPayIframeTag;

class EmPayPaymentStrategy implements ICreditCardStrategy
{

    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function get()
    {
        return new EPayIframeTag();
    }
}