<?php


namespace EuroMillions\web\vo\dto;


class SiteConfigDTO implements \JsonSerializable
{

    public $feeLimit;
    public $fee;
    public $feeLimitConverted;
    public $feeConverted;
    public $bundleData;
    public $bundleDataDTO;
    public $bundlePlayDTOActive;

    public function __construct( $feeLimit, $fee, $feeLimitConverted, $feeConverted, array $bundleData = null)
    {
        $this->feeLimit = $feeLimit;
        $this->fee = $fee;
        $this->feeLimitConverted = $feeLimitConverted;
        $this->feeConverted = $feeConverted;
        $this->bundleData = $bundleData;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return $this->bundleData;
    }
}