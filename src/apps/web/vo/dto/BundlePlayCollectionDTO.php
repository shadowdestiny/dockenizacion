<?php

namespace EuroMillions\web\vo\dto;


class BundlePlayCollectionDTO implements \JsonSerializable
{
    /** @var BundlePlayDTO $bundlePlayDTO */
    public $bundlePlayDTO;
    /** @var  BundlePlayDTO $bundlePlayDTOActive */
    public $bundlePlayDTOActive;
    protected $betSinglePrice;
    protected $arrayBundle;

    public function __construct(array $arrayBundle, $betSinglePrice)
    {
        $this->arrayBundle = $arrayBundle;
        $this->betSinglePrice = $betSinglePrice;
        $this->bundlePlayDTO = $this->getBundleData($arrayBundle);
    }

    /**
     * @param array $bundleData
     * @return array
     */
    public function getBundleData($bundleData){
        $arrayBundleData = [];
        if ($bundleData) {
            foreach ($bundleData as $value) {
                $arrayBundleData[] = new BundlePlayDTO($value, $this->betSinglePrice);

                if ($value['checked'] == 'active') {
                    $this->bundlePlayDTOActive = new BundlePlayDTO($value, $this->betSinglePrice);
                }
            }
        }
        return $arrayBundleData;
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
        return $this->bundlePlayDTO;
    }
}