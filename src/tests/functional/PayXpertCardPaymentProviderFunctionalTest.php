<?php
namespace tests\functional;

use EuroMillions\web\services\card_payment_providers\payxpert\PayXpertConfig;
use EuroMillions\web\services\card_payment_providers\PayXpertCardPaymentProvider;
use Money\Currency;
use Money\Money;
use tests\base\DatabaseIntegrationTestBase;
use tests\helpers\mothers\CreditCardMother;

class PayXpertCardPaymentProviderFunctionalTest extends DatabaseIntegrationTestBase
{
    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    protected function getFixtures()
    {
        return [];
    }

    /**
     * method charge
     * when calledWithValidCreditCard
     * should returnProperResponse
     */
    public function test_charge_calledWithValidCreditCard_returnProperResponse()
    {
        $sut = new PayXpertCardPaymentProvider(new PayXpertConfig('103893','Panam Test Site', '5}G,,5[L.A~*&/{h'));
        var_dump($sut->charge(new Money(10000, new Currency('EUR')), CreditCardMother::aValidCreditCard()));
    }

}