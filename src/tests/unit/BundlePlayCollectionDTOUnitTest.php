<?php

use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\vo\dto\BundlePlayCollectionDTO;
use EuroMillions\web\vo\dto\BundlePlayDTO;
use Money\Currency;
use Money\Money;

class BundlePlayCollectionDTOUnitTest extends UnitTestBase
{
    /**
     * method construct
     * when called
     * should returnArrayCollectionBundlePlayDTO
     */
    public function test_construct_called_returnArrayCollectionBundlePlayDTO(){
        $bundlePlayCollection = new BundlePlayCollectionDTO($this->bundlePlayData(), new Money(250, new Currency('EUR')));
        $expectedBundlePlay = $this->expectedBundlePlay();
        $this->assertEquals($expectedBundlePlay[0], $bundlePlayCollection->bundlePlayDTO[0]);
        $this->assertEquals($expectedBundlePlay[1], $bundlePlayCollection->bundlePlayDTO[1]);
        $this->assertEquals($expectedBundlePlay[0], $bundlePlayCollection->bundlePlayDTOActive);
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

    private function expectedBundlePlay() {
        $expectedBundlePlay = [];
        $expectedBundlePlay[] = new BundlePlayDTO(['draws' => '1', 'description' => '1 Draw', 'price_description' => 'Line', 'price' => '3', 'discount' => 0, 'checked' => 'active'], new Money(250, new Currency('EUR')));
        $expectedBundlePlay[] = new BundlePlayDTO(['draws' => '4', 'description' => '4 Draws', 'price_description' => 'Line', 'price' => '3', 'discount' => 0, 'checked' => ''], new Money(250, new Currency('EUR')));
        return $expectedBundlePlay;
    }
}
