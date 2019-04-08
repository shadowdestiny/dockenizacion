<?php


namespace tests\unit;


use EuroMillions\shared\vo\results\PaymentProviderResult;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\PaymentProviderMother;
use EuroMillions\web\services\card_payment_providers\royalpay\dto\RoyalPayBodyResponse;
use EuroMillions\web\services\card_payment_providers\RoyalPayPaymentProvider;
use EuroMillions\web\services\card_payment_providers\royalpay\RoyalPayConfig;
use Phalcon\Http\Client\Response;
use Prophecy\Argument;

class RoyalPayPaymentProviderUnitTest extends UnitTestBase
{


    private $gatewayClient_double;


    public function setUp()
    {
        parent::setUp();
        $this->gatewayClient_double = $this->prophesize('EuroMillions\web\services\card_payment_providers\royalpay\GatewayClientWrapper');
    }

    /**
     * method charge
     * when calledWithValidParams
     * should returnPaymentProviderResultSuccess
     */
    public function test_charge_calledWithValidParams_returnPaymentProviderResultSuccess()
    {
        $body = new \stdClass();
        $body->status= 'created';
        $expected = new PaymentProviderResult(true, new RoyalPayBodyResponse($body, ""));
        $response = new Response();
        $response->header->statusCode = 201;
        $response->body = '{ "status": "created" }';
        $this->gatewayClient_double->send(Argument::type('Array'), 'payment')->willReturn($response);
        $royalPayProvider = new RoyalPayPaymentProvider(new RoyalPayConfig('',''),$this->gatewayClient_double->reveal());
        $paymentProviderDTO = PaymentProviderMother::aPaymentProvider($royalPayProvider);
        $actual = $royalPayProvider->charge($paymentProviderDTO);
        $this->assertEquals($expected,$actual);
    }


    /**
     * method charge
     * when calledWithInvalidParams
     * should returnPaymentProviderResultFalse
     */
    public function test_charge_calledWithInvalidParams_returnPaymentProviderResultFalse()
    {
        $body = new \stdClass();
        $body->status= false;
        $expected = new PaymentProviderResult(false, new RoyalPayBodyResponse($body, ""));
        $response = new Response();
        $response->header->statusCode = 201;
        $response->body = '{ "status": "error" }';
        $this->gatewayClient_double->send(Argument::type('Array'), 'payment')->willReturn($response);
        $royalPayProvider = new RoyalPayPaymentProvider(new RoyalPayConfig('',''),$this->gatewayClient_double->reveal());
        $paymentProviderDTO = PaymentProviderMother::aPaymentProvider($royalPayProvider);
        $actual = $royalPayProvider->charge($paymentProviderDTO);
        $this->assertEquals($expected,$actual);

    }
}