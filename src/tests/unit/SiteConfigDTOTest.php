<?php

use EuroMillions\web\vo\dto\BundlePlayDTO;
use EuroMillions\web\vo\dto\SiteConfigDTO;
use EuroMillions\tests\base\UnitTestBase;

class SiteConfigDTOTest extends UnitTestBase
{

    /**
     * method getBundleData
     * when called
     * should returnArrayCollectionBundlePlayDTO
     */
    public function test_getBundleData_called_returnArrayCollectionBundlePlayDTO(){
        $expectedBundleData = [
            new BundlePlayDTO(1, '1 Draw', 'Line', '3', 0, 'active'),
            new BundlePlayDTO(4, '4 Draws', 'Line', '3', 0, ''),
        ];
        $expectedBundleDataActive = new BundlePlayDTO(1, '1 Draw', 'Line', 3, 0, 'active');
        $bundleData = $this->getSiteConfigDTO($this->bundlePlayData());

        $this->assertEquals($expectedBundleData, $bundleData->bundleDataDTO);
        $this->assertEquals($expectedBundleDataActive, $bundleData->bundlePlayDTOActive);
    }

    /**
     * method getBundleData
     * when called
     * should returnEmptyArray
     */
    public function test_getBundleData_called_returnEmptyArrayInBundleData(){
        $this->assertSame([], $this->getSiteConfigDTO()->bundleDataDTO);
        $this->assertSame(null, $this->getSiteConfigDTO()->bundlePlayDTOActive);
    }

    /**
     * @param $bundleData
     * @return SiteConfigDTO
     */
    private function getSiteConfigDTO($bundleData = null){
        return new SiteConfigDTO('€12.00', '€0.35', 1200, 35, $bundleData);
    }

    /**
     * @return array
     */
    private function bundlePlayData()
    {
        return [
            ['draws' => '1', 'description' => '1 Draw', 'price_description' => 'Line', 'price' => '3', 'discount' => 0, 'checked' => 'active'],
            ['draws' => '4', 'description' => '4 Draws', 'price_description' => 'Line', 'price' => '3', 'discount' => 0, 'checked' => ''],
        ];
    }
}
