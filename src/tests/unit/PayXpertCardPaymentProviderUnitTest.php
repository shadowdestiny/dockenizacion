<?php
namespace tests\unit;

use EuroMillions\web\services\card_payment_providers\payxpert\PayXpertConfig;
use EuroMillions\web\services\card_payment_providers\PayXpertCardPaymentProvider;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use tests\base\UnitTestBase;
use tests\helpers\mothers\CreditCardMother;

class PayXpertCardPaymentProviderUnitTest extends UnitTestBase
{
    /**
     * method charge
     * when gatewayReturnsOkResult
     * should returnResultTrueWithGatewayResult
     */
    public function test_charge_gatewayReturnsOkResult_returnResultTrueWithGatewayResult()
    {
        $this->exerciseAndAssertCharge(
            CreditCardMother::aValidCreditCard(),
            true,
            '000',
            'Transaction successfully completed'
        );
    }

    /**
     * method charge
     * when gatewayReturnKoResult
     * should returnResultFalseWithGatewayErrorMessage
     */
    public function test_charge_gatewayReturnKoResult_returnResultFalseWithGatewayErrorMessage()
    {
        $this->exerciseAndAssertCharge(
            CreditCardMother::aValidCreditCard(),
            false,
            '004',
            'Invalid card'
        );
    }

    /**
     * @param $creditCard
     * @param $success
     * @param $expected_result
     * @param $errorCode
     * @param $errorMessage
     */
    private function exerciseAndAssertCharge($creditCard, $success, $errorCode, $errorMessage)
    {
        $expected_result = (object) [
            'errorCode' => $errorCode,
            'errorMessage' => $errorMessage
        ];
        $api_key = 'disjfs';
        $originator_name = 'dlsfklds';
        $originator_id = '111';
        $amount = '3000';
        $currency = 'EUR';
        $money = new Money((int)$amount, new Currency($currency));
        $gateway = $this->prophesize('\EuroMillions\web\services\card_payment_providers\payxpert\GatewayClientWrapper');
        $transaction = $this->prophesize('\EuroMillions\web\services\card_payment_providers\payxpert\GatewayTransactionWrapper');

        $transaction->setTransactionInformation(
            $amount,
            $currency,
            'EuroMillions Wallet Recharge'
        )->shouldBeCalled();
        $transaction->setCardInformation(
            $creditCard->getCardNumbers(),
            $creditCard->getCVV(),
            $creditCard->getHolderName(),
            $creditCard->getExpiryMonth(),
            $creditCard->getExpiryYear()
        )->shouldBeCalled();
        $transaction->setShopperInformation(
            Argument::any(),
            Argument::any(),
            Argument::any(),
            Argument::any(),
            Argument::any(),
            Argument::any(),
            Argument::any(),
            Argument::any()
        )->shouldBeCalled();

        $transaction->send()->willReturn($expected_result);

        $gateway->newTransaction('CCSale')->willReturn($transaction->reveal());


        $sut = new PayXpertCardPaymentProvider(new PayXpertConfig($originator_id, $originator_name, $api_key), $gateway->reveal());
        $actual = $sut->charge($money, $creditCard);
        $this->assertEquals($success, $actual->success());
        $this->assertEquals($expected_result, $actual->returnValues());
    }
}