<?php


namespace EuroMillions\web\services\card_payment_providers\royalpay;


use Phalcon\Di;

class RoyalPayConfig
{

    private $endpoint;
    private $endpoint_callbacks;
    private $apiKey;
    private $merchantDomain;

    public function __construct($endpoint, $apiKey)
    {
        $di = Di::getDefault();

        $this->endpoint = $endpoint;
        $this->endpoint_callbacks = $endpoint."/notification";
        $this->apiKey = $apiKey;
        $this->merchantDomain = $di->get('config')['domain']['url'];
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