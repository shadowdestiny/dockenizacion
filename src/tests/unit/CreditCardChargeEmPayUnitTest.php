<?php


namespace EuroMillions\tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\vo\CreditCardChargeEmPay;
use Money\Currency;
use Money\Money;

class CreditCardChargeEmPayUnitTest extends UnitTestBase
{


    /**
     * method getAmount
     * when called
     * should returnAmountWithOutFee
     */
    public function test_getAmount_called_returnAmountWithOutFee()
    {
        $amount = new Money(1210, new Currency('EUR'));
        $fee = new Money(35, new Currency('EUR'));
        $feeLimit = new Money(1200, new Currency('EUR'));
        $sut = new CreditCardChargeEmPay($amount, $fee, $feeLimit);
        $actual = $sut->getAmount();
        $expected = new Money(1210, new Currency('EUR'));
        $this->assertEquals($expected,$actual);
    }

}