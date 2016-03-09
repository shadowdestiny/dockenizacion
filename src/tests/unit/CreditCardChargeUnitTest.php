<?php


namespace EuroMillions\tests\unit;


use Money\Currency;
use Money\Money;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\CreditCardChargeMother;

class CreditCardChargeUnitTest extends UnitTestBase
{

    /**
     * method getFinalAmount
     * when called
     * should returnAmountWithFeeCharge
     */
    public function test_getFinalAmount_called_returnAmountWithFeeCharge()
    {
        $expected = new Money(10035, new Currency('EUR'));
        $sut = CreditCardChargeMother::aValidCreditCardChargeWithFee();
        $actual = $sut->getFinalAmount();
        $this->assertEquals($expected, $actual);
    }

    /**
     * method getFinalAmount
     * when called
     * should returnAmountWithoutFeeCharge
     */
    public function test_getFinalAmount_called_returnAmountWithoutFeeCharge()
    {
        $expected = new Money(14000, new Currency('EUR'));
        $sut = CreditCardChargeMother::aValidCreditCardChargeWithoutFee();
        $actual = $sut->getFinalAmount();
        $this->assertEquals($expected, $actual);
    }

}