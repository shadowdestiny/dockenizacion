<?php


namespace EuroMillions\web\services\card_payment_providers\gcp;


class GCPConfig
{

    protected $url;
    protected $merchantId;
    protected $visaGateway;
    protected $masterGateway;
    protected $visaSignKey;
    protected $masterSignKey;


    public function __construct($url, $merchantId, $visaGateway, $masterGateway, $visaSignKey, $masterSignKey)
    {
        $this->url = $url;
        $this->merchantId = $merchantId;
        $this->visaGateway = $visaGateway;
        $this->masterGateway = $masterGateway;
        $this->visaSignKey = $visaSignKey;
        $this->masterSignKey = $masterSignKey;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @param mixed $merchantId
     */
    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;
    }

    /**
     * @return mixed
     */
    public function getVisaGateway()
    {
        return $this->visaGateway;
    }

    /**
     * @param mixed $visaGateway
     */
    public function setVisaGateway($visaGateway)
    {
        $this->visaGateway = $visaGateway;
    }

    /**
     * @return mixed
     */
    public function getMasterGateway()
    {
        return $this->masterGateway;
    }

    /**
     * @param mixed $masterGateway
     */
    public function setMasterGateway($masterGateway)
    {
        $this->masterGateway = $masterGateway;
    }

    /**
     * @return mixed
     */
    public function getVisaSignKey()
    {
        return $this->visaSignKey;
    }

    /**
     * @param mixed $visaSignKey
     */
    public function setVisaSignKey($visaSignKey)
    {
        $this->visaSignKey = $visaSignKey;
    }

    /**
     * @return mixed
     */
    public function getMasterSignKey()
    {
        return $this->masterSignKey;
    }

    /**
     * @param mixed $masterSignKey
     */
    public function setMasterSignKey($masterSignKey)
    {
        $this->masterSignKey = $masterSignKey;
    }

}