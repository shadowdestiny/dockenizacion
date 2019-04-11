<?php

namespace EuroMillions\web\services\card_payment_providers\shared;

abstract class CardPaymentProviderConfig
{

    private $endpoint;
    private $apiKey;
    private $filterConfig;

    public function __construct($endpoint, $apiKey, $weight = 100, $countries = null)
    {
        $this->endpoint = $endpoint;
        $this->apiKey = $apiKey;


        $this->filterConfig = new CardPaymentProviderConfigFilter(
            (int) $weight,
            $countries
        );
    }

    /**
     * @return mixed
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param mixed $endpoint
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param mixed $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return CardPaymentProviderConfigFilter
     */
    public function getFilterConfig()
    {
        return $this->filterConfig;
    }
}
