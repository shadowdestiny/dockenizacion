<?php


namespace tests\unit;


use EuroMillions\shared\vo\results\PaymentProviderResult;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\PaymentProviderMother;
use EuroMillions\web\services\card_payment_providers\widecard\WideCardConfig;
use EuroMillions\web\services\card_payment_providers\WideCardPaymentProvider;
use EuroMillions\web\vo\dto\PaymentProviderDTO;
use EuroMillions\web\vo\dto\RoyalPayPaymentProviderDTO;
use Phalcon\Http\Client\Response;
use Prophecy\Argument;

class WideCardPaymentProviderUnitTest extends UnitTestBase
{


    private $gatewayClient_double;


    public function setUp()
    {
        parent::setUp();
        $this->gatewayClient_double = $this->prophesize('EuroMillions\web\services\card_payment_providers\widecard\GatewayClientWrapper');
    }


    /**
     * method charge
     * when calledWithValidParams
     * should returnPaymentProviderResultSuccess
     */
    public function test_charge_calledWithValidParams_returnPaymentProviderResultSuccess()
    {
        $expected = new PaymentProviderResult(true);
        $response = new Response();
        $response->header->statusCode = 200;
        $response->body = '{ "status": "ok" }';
        $this->gatewayClient_double->send(Argument::type('Array'))->willReturn($response);
        $wideCardProvider = new WideCardPaymentProvider(new WideCardConfig([],''),$this->gatewayClient_double->reveal());
        $paymentProviderDTO = PaymentProviderMother::aPaymentProvider($wideCardProvider);
        $actual = $wideCardProvider->charge($paymentProviderDTO);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method charge
     * when calledWithInvalidParams
     * should returnPaymentProviderResultFalse
     */
    public function test_charge_calledWithInvalidParams_returnPaymentProviderResultFalse()
    {
        $expected = new PaymentProviderResult(false);
        $response = new Response();
        $response->header->statusCode = 200;
        $response->body = '{ "status": "ko" }';
        $this->gatewayClient_double->send(Argument::type('Array'))->willReturn($response);
        $wideCardProvider = new WideCardPaymentProvider(new WideCardConfig([],''),$this->gatewayClient_double->reveal());
        $paymentProviderDTO = PaymentProviderMother::aPaymentProvider($wideCardProvider);
        $actual = $wideCardProvider->charge($paymentProviderDTO);
        $this->assertEquals($expected,$actual);

    }
}