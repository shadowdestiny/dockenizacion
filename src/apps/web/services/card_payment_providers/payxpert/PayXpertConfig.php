<?php
namespace EuroMillions\web\services\card_payment_providers\payxpert;

class PayXpertConfig
{
    const URL = 'https://api.payxpert.com/';
    private $originatorId;
    private $originatorName;
    private $apiKey;

    /**
     * PayXpertConfig constructor.
     * @param $originatorId
     * @param $originatorName
     * @param $apiKey
     */
    public function __construct($originatorId, $originatorName, $apiKey)
    {
        $this->originatorId = $originatorId;
        $this->originatorName = $originatorName;
        $this->apiKey = $apiKey;
    }

    /**
     * @return mixed
     */
    public function getOriginatorId()
    {
        return $this->originatorId;
    }

    /**
     * @return mixed
     */
    public function getOriginatorName()
    {
        return $this->originatorName;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }
}