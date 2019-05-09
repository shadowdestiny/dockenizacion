<?php


namespace tests\unit;

use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\services\card_payment_providers\moneymatrix\MoneyMatrixConfig;
use EuroMillions\web\services\card_payment_providers\shared\CardPaymentProviderConfigFilter;
use EuroMillions\web\services\card_payment_providers\shared\CountriesCollection;

class MoneyMatrixConfigUnitTest extends UnitTestBase
{
    use CountriesCollection;

    /**
    * method getFilterConfig
    * when validCountriesString
    * should returnValidArrayOfCountries
    */
    public function test_setCountries__validCountriesString__returnValidArrayOfCountries()
    {
        $countries = ['ES','UA','RU'];
        $weight = 100;

        $expected = new CardPaymentProviderConfigFilter($weight, implode(",", $countries));

        $sut = new MoneyMatrixConfig('', '', $weight, implode(",", $countries));
        $actual = $sut->getFilterConfig();
        $this->assertEquals($expected, $actual);
    }

    /**
     * method getFilterConfig
     * when emptyString
     * should returnValidArrayOfFullCountries
     */
    public function test_setCountries__emptyString__returnValidArrayOfFullCountries()
    {
        $countries = $this->countries();
        $weight = 100;
        $expected = new CardPaymentProviderConfigFilter($weight, implode(",", $countries));

        $sut = new MoneyMatrixConfig('', '', 100, '');
        $actual = $sut->getFilterConfig();
        $this->assertEquals($expected, $actual);
    }

    /**
     * method getFilterConfig
     * when null
     * should returnValidArrayOfFullCountries
     */
    public function test_setCountries__null__returnValidArrayOfFullCountries()
    {
        $countries = $this->countries();
        $weight = 100;
        $expected = new CardPaymentProviderConfigFilter($weight, implode(",", $countries));

        $sut = new MoneyMatrixConfig('', '');
        $actual = $sut->getFilterConfig();
        $this->assertEquals($expected, $actual);
    }
}
