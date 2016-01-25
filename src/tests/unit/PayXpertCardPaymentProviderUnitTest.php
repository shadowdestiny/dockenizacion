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
        $api_key = 'disjfs';
        $originator_name = 'dlsfklds';
        $originator_id = '111';
        $amount = '3000';
        $currency = 'EUR';
        $money = new Money((int)$amount, new Currency($currency));
        $gateway = $this->prophesize('\EuroMillions\web\services\card_payment_providers\payxpert\GatewayClientWrapper');
        $transaction = $this->prophesize('\EuroMillions\web\services\card_payment_providers\payxpert\GatewayTransactionWrapper');
        $credit_card = CreditCardMother::aValidCreditCard();

        $transaction->setTransactionInformation(
            $amount,
            $currency,
            'EuroMillions Wallet Recharge'
        )->shouldBeCalled();
        $transaction->setCardInformation(
            $credit_card->getCardNumbers(),
            $credit_card->getCVV(),
            $credit_card->getHolderName(),
            $credit_card->getExpiryMonth(),
            $credit_card->getExpiryYear()
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
        $expected_result = new \stdClass();
        $transaction->send()->willReturn($expected_result);

        $gateway->newTransaction('CCSale')->willReturn($transaction->reveal());


        $sut = new PayXpertCardPaymentProvider(new PayXpertConfig($originator_id, $originator_name, $api_key), $gateway->reveal());
        $actual = $sut->charge($money, $credit_card);
        $this->assertTrue($actual->success());
        $this->assertEquals($expected_result, $actual->returnValues());
    }
}