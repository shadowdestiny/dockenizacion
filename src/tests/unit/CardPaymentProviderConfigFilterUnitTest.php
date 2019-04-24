<?php


namespace tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\services\card_payment_providers\shared\CardPaymentProviderConfigFilter;
use EuroMillions\web\services\card_payment_providers\shared\CountriesCollection;
use EuroMillions\web\vo\PaymentCountry;
use EuroMillions\web\vo\PaymentWeight;

class CardPaymentProviderConfigFilterUnitTest extends UnitTestBase
{
    use CountriesCollection;

    /**
    * method getCountries
    * when validCountriesString
    * should returnValidPaymentCountry
    */
    public function test_getCountries__validCountriesString__returnValidArrayOfCountries()
    {
        $data = ['ES','UA','RU'];
        $expected = new PaymentCountry($data);

        $sut = new CardPaymentProviderConfigFilter(100, implode(",", $data));
        $actual = $sut->getCountries();
        $this->assertEquals($expected, $actual);
    }

    /**
     * method getCountries
     * when emptyString
     * should returnValidArrayOfFullCountries
     */
    public function test_getCountries__emptyString__returnValidArrayOfFullCountries()
    {
        $expected = new PaymentCountry($this->countries());

        $sut = new CardPaymentProviderConfigFilter(0,'');
        $actual = $sut->getCountries();
        $this->assertEquals($expected, $actual);
    }

    /**
     * method getCountries
     * when null
     * should returnValidArrayOfFullCountries
     */
    public function test_getCountries__null__returnValidArrayOfFullCountries()
    {
        $expected = new PaymentCountry($this->countries());

        $sut = new CardPaymentProviderConfigFilter();
        $actual = $sut->getCountries();
        $this->assertEquals($expected, $actual);
    }

    /**
    * method getWeight
    * when called
    * should returnValidPaymentWeight
    */
    public function test_getWeight__called__returnValidPaymentWeight()
    {
        $data = 10;
        $expected = new PaymentWeight($data);

        $sut = new CardPaymentProviderConfigFilter($data);
        $actual = $sut->getWeight();
        $this->assertEquals($expected, $actual);
    }
}
