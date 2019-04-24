<?php
namespace tests\unit;

use EuroMillions\shared\vo\results\PaymentProviderResult;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\PaymentProviderMother;
use EuroMillions\web\services\card_payment_providers\emerchant\eMerchantConfig;
use EuroMillions\web\services\card_payment_providers\eMerchantPaymentProvider;
use Phalcon\Http\Client\Response;
use Prophecy\Argument;

class eMerchantPaymentProviderTest extends UnitTestBase
{

    private $gatewayClient_double;

    public function setUp()
    {
        parent::setUp();
        $this->gatewayClient_double = $this->prophesize('EuroMillions\web\services\card_payment_providers\emerchant\GatewayClientWrapper');
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
        $eMerchantProvider = new eMerchantPaymentProvider(new eMerchantConfig([],''),$this->gatewayClient_double->reveal());
        $paymentProviderDTO = PaymentProviderMother::aPaymentProvider($eMerchantProvider);
        $actual = $eMerchantProvider->charge($paymentProviderDTO);
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
        $eMerchantProvider = new eMerchantPaymentProvider(new eMerchantConfig([],''),$this->gatewayClient_double->reveal());
        $paymentProviderDTO = PaymentProviderMother::aPaymentProvider($eMerchantProvider);
        $actual = $eMerchantProvider->charge($paymentProviderDTO);
        $this->assertEquals($expected,$actual);
    }
}
