<?php
namespace tests\unit;

use EuroMillions\web\entities\User;
use Money\Currency;
use tests\base\UnitTestBase;

class UserUnitTest extends UnitTestBase
{
    /**
     * method getUserCurrency
     * when userCurrencyIsNull
     * should returnEurCurrency
     */
    public function test_getUserCurrency_userCurrencyIsNull_returnEurCurrency()
    {
        $sut = new User();
        $actual = $sut->getUserCurrency();
        $expected = new Currency('EUR');
        $this->assertEquals($expected, $actual);
    }
}