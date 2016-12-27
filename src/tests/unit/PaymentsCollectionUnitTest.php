<?php


namespace EuroMillions\tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\services\card_payment_providers\payments_util\PaymentsCollection;

class PaymentsCollectionUnitTest extends UnitTestBase
{


    public function setUp()
    {
        parent::setUp();
    }

    /**
     * method getAll
     * when called
     * should returnACollectionFromGateways
     */
    public function test_getAll_called_returnACollectionFromGateways()
    {
        $sut = $this->getSut();
        $this->assertSame(2, count($sut->getAll()));
    }


    private function getSut()
    {
        $interface = "EuroMillions\\web\\services\\card_payment_providers\\ICreditCardStrategy";
        $classes = array_filter(get_declared_classes(), create_function('$className', "return in_array(\"{$interface}\", class_implements(\"\$className\"));"));
        return new PaymentsCollection($classes);
    }


}