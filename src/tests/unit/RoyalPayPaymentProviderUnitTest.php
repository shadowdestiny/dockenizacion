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
        $body->status = 'created';
        $body->message = '';
        $body->redirect_url = 'https://foo.bar';
        $body->redirect_method = 'GET';

        $params = new \stdClass();
        $params->param1 = "value1";
        $params->param2 = "value2";
        $body->redirect_params = $params;

        $expected = new PaymentProviderResult(true, new RoyalPayBodyResponse($body, ""));
        $response = new Response();
        $response->header->statusCode = 201;
        $response->body = '{ 
            "status": "created",
            "url": "",
            "redirect_url": "https://foo.bar",
            "redirect_method": "GET",
            "redirect_params": {
                "param1": "value1",
                "param2": "value2"
            }   
        }';
        $this->gatewayClient_double->send(Argument::type('Array'), 'payment')->willReturn($response);
        $royalPayProvider = new RoyalPayPaymentProvider(new RoyalPayConfig('', ''), $this->gatewayClient_double->reveal());
        $paymentProviderDTO = PaymentProviderMother::aPaymentProvider($royalPayProvider);
        $actual = $royalPayProvider->charge($paymentProviderDTO);
        $this->assertEquals($expected, $actual);
    }


    /**
     * method charge
     * when calledWithInvalidParams
     * should returnPaymentProviderResultFalse
     */
    public function test_charge_calledWithInvalidParams_returnPaymentProviderResultFalse()
    {
        $body = new \stdClass();
        $body->status = false;
        $body->message = '';
        $body->redirect_url = 'https://foo.bar';
        $body->redirect_method = 'GET';

        $params = new \stdClass();
        $params->param1 = "value1";
        $params->param2 = "value2";
        $body->redirect_params = $params;

        $expected = new PaymentProviderResult(false, new RoyalPayBodyResponse($body, ""));
        $response = new Response();
        $response->header->statusCode = 201;
        $response->body = '{ 
            "status": "error",
            "url": "",
            "redirect_url": "https://foo.bar",
            "redirect_method": "GET",
            "redirect_params": {
                "param1": "value1",
                "param2": "value2"
            }   
        }';
        $this->gatewayClient_double->send(Argument::type('Array'), 'payment')->willReturn($response);
        $royalPayProvider = new RoyalPayPaymentProvider(new RoyalPayConfig('', ''), $this->gatewayClient_double->reveal());
        $paymentProviderDTO = PaymentProviderMother::aPaymentProvider($royalPayProvider);
        $actual = $royalPayProvider->charge($paymentProviderDTO);
        $this->assertEquals($expected, $actual);
    }
}
