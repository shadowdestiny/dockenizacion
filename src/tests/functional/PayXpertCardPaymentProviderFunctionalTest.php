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
        $this->exerciseAndAssertCharge(
            CreditCardMother::aValidCreditCard(),
            true,
            "000",
            "Transaction successfully completed"
        );
    }

    /**
     * method charge
     * when calledWithAndInvalidCreditCard
     * should returnErrorResponse
     */
    public function test_charge_calledWithAndInvalidCreditCard_returnErrorResponse()
    {
        $this->exerciseAndAssertCharge(
            CreditCardMother::anInvalidCreditCard(),
            false,
            "004",
            "Invalid card"
        );
    }

    /**
     * @param $card
     * @return \EuroMillions\shared\vo\results\PaymentProviderResult
     */
    private function exerciseCharge($card)
    {
        $sut = new PayXpertCardPaymentProvider(new PayXpertConfig('103893', 'Panam Test Site', '5}G,,5[L.A~*&/{h'));
        return $sut->charge(new Money(10000, new Currency('EUR')), $card);
    }

    /**
     * @param $card
     * @param $success
     * @param $error_code
     * @param $error_message
     */
    private function exerciseAndAssertCharge($card, $success, $error_code, $error_message)
    {
        $actual = $this->exerciseCharge($card);
        $this->assertEquals($success, $actual->success());
        $this->assertEquals($error_code, $actual->returnValues()->errorCode);
        $this->assertEquals($error_message, $actual->returnValues()->errorMessage);
    }

}