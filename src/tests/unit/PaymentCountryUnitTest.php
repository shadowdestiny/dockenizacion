<?php


namespace EuroMillions\tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\vo\PaymentCountry;

class PaymentCountryUnitTest extends UnitTestBase
{

    /**
     * method __construct
     * when called
     * should makePaymentCountryObject
     */
    public function test___construct_called_makePaymentCountryObject()
    {
        list($arr, $expected) = $this->prepareData();
        $actual = new PaymentCountry($arr);
        $this->assertEquals($expected,$actual->countries());
    }

    /**
     * method createPaymentCountry
     * when called
     * should makePaymentCountryObject
     */
    public function test_createPaymentCountry_called_makePaymentCountryObject()
    {
        list($arr, $expected) = $this->prepareData();
        $actual = PaymentCountry::createPaymentCountry($arr);
        $this->assertEquals($expected,$actual->countries());
    }

    /**
     * @return array
     */
    protected function prepareData()
    {
        $arr = ['ES', 'DE', 'IT'];
        $expected = $arr;
        return array($arr, $expected);
    }


}