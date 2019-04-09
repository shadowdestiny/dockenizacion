<?php

namespace EuroMillions\web\services\card_payment_providers\royalpay;

use EuroMillions\web\services\card_payment_providers\shared\CardPaymentProviderConfig;
use Phalcon\Di;

class RoyalPayConfig extends CardPaymentProviderConfig
{
    private $endpoint_callbacks;
    private $merchantDomain;

    public function __construct($endpoint, $apiKey, $weight = 100, $countries = null)
    {
        parent::__construct($endpoint, $apiKey, $weight, $countries);

        $di = Di::getDefault();

        $this->endpoint_callbacks = $endpoint."/notification";
        $this->merchantDomain = $di->get('config')['domain']['url'];
    }

    /**
     * @return string
     */
    public function getMerchantDomain()
    {
        return $this->merchantDomain;
    }

    /**
     * @param string $merchantDomain
     */
    public function setMerchantDomain($merchantDomain)
    {
        $this->merchantDomain = $merchantDomain;
    }

    /**
     * @return string
     */
    public function getEndpointCallbacks()
    {
        return $this->endpoint_callbacks;
    }

    /**
     * @param string $endpoint_callbacks
     */
    public function setEndpointCallbacks($endpoint_callbacks)
    {
        $this->endpoint_callbacks = $endpoint_callbacks;
    }
}
