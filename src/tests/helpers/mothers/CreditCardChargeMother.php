<?php


namespace EuroMillions\tests\helpers\mothers;

use EuroMillions\web\vo\CreditCardCharge;
use Money\Currency;
use Money\Money;

class CreditCardChargeMother
{

    /**
     * @returns CreditCardCharge
     */
    public static function aValidCreditCardChargeWithFee()
    {
        $amount = new Money(10000, new Currency('EUR'));
        $fee = new Money(35, new Currency('EUR'));
        $fee_limit = new Money(12000, new Currency('EUR'));
        return new CreditCardCharge( $amount, $fee, $fee_limit );
    }

    /**
     * @returns CreditCardCharge
     */
    public static function aValidCreditCardChargeWithoutFee()
    {
        $amount = new Money(14000, new Currency('EUR'));
        $fee = new Money(35, new Currency('EUR'));
        $fee_limit = new Money(12000, new Currency('EUR'));
        return new CreditCardCharge( $amount, $fee, $fee_limit );
    }

    public static function aValidCreditCardChargeWithAmountInParam( Money $amount)
    {
        $fee = new Money(35, new Currency('EUR'));
        $fee_limit = new Money(12000, new Currency('EUR'));
        return new CreditCardCharge( $amount, $fee, $fee_limit );
    }

}