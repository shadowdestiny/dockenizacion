<?php


namespace EuroMillions\web\services\card_payment_providers\widecard;


class WideCardConfig
{

    private $endpoint;
    private $apiKey;

    public function __construct($endpoint, $apiKey)
    {
        $this->endpoint = $endpoint;
        $this->apiKey = $apiKey;
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

}