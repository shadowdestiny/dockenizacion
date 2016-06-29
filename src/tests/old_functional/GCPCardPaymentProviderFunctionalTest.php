<?php


namespace EuroMillions\tests\old_functional;


use EuroMillions\shared\vo\results\PaymentProviderResult;
use EuroMillions\tests\base\DatabaseIntegrationTestBase;
use EuroMillions\tests\helpers\mothers\CreditCardMother;
use EuroMillions\tests\helpers\mothers\OrderMother;
use EuroMillions\web\services\card_payment_providers\gcp\GCPConfig;
use EuroMillions\web\services\card_payment_providers\GCPCardPaymentProvider;
use Money\Currency;
use Money\Money;

class GCPCardPaymentProviderFunctionalTest extends DatabaseIntegrationTestBase
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
     * when calledWithValidCreditCardAndOrder
     * should returnProperResponse
     */
    public function test_charge_calledWithValidCreditCardAndOrder_returnProperResponse()
    {
        $card = CreditCardMother::aValidCreditCard();
        $order = OrderMother::aJustOrder()->build();
        $sut = $this->exerciseCharge();
        $sut->order($order);
        $expected = new PaymentProviderResult(true);
        $actual = $sut->charge(new Money(1000, new Currency('EUR')), $card);
        $this->assertEquals($expected,$actual);
    }


    private function exerciseCharge()
    {
        $sut = new GCPCardPaymentProvider(new GCPConfig('https://online-safest.com/DirectInterface',
                                                        '21260',
                                                        '21260001',
                                                        '21260002',
                                                        'Hn6fjbB8',
                                                        '6f6pB46l')
                                            );
        //return $sut->charge(new Money(10000, $currency),$card);
        return $sut;
    }
}