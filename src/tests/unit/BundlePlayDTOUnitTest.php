<?php

use EuroMillions\web\vo\dto\BundlePlayDTO;
use Money\Currency;
use Money\Money;

class BundlePlayDTOTest extends PHPUnit_Framework_TestCase
{
    /**
     * method construct
     * when called
     * should BundlePlayDTO
     */
    public function test_construct_called_returnArrayCollectionBundlePlayDTO(){
        $bundlePlayDTO = new BundlePlayDTO(['draws' => '1', 'description' => '1 Draw', 'price_description' => 'Line', 'price' => '3', 'discount' => 1.25, 'checked' => 'active'], new Money(250, new Currency('EUR')));
        $this->assertSame('1', $bundlePlayDTO->getDraws());
        $this->assertSame('1 Draw', $bundlePlayDTO->getDescription());
        $this->assertSame('Line', $bundlePlayDTO->getPriceDescription());
        $this->assertSame('3', $bundlePlayDTO->getPrice());
        $this->assertSame(1.25, $bundlePlayDTO->getDiscount());
        $this->assertSame('active', $bundlePlayDTO->getActive());
        $this->assertSame(250, $bundlePlayDTO->singleBetPrice->getAmount());
        $this->assertSame(247, $bundlePlayDTO->singleBetPriceWithDiscount->getAmount());
    }
}
