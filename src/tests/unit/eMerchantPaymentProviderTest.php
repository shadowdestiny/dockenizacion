<?php
namespace tests\unit;

use EuroMillions\shared\vo\results\PaymentProviderResult;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\CreditCardMother;
use EuroMillions\tests\helpers\mothers\OrderMother;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\services\card_payment_providers\emerchant\eMerchantConfig;
use EuroMillions\web\services\card_payment_providers\eMerchantPaymentProvider;
use EuroMillions\web\vo\dto\PaymentProviderDTO;
use EuroMillions\web\vo\dto\UserDTO;
use Money\Currency;
use Money\Money;
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
        $amount = new Money(10000, new Currency('EUR'));
        $card = CreditCardMother::aValidCreditCard();
        $user = UserMother::aJustRegisteredUser()->build();
        $order = OrderMother::aJustOrder()->buildANewWay();
        $response = new Response();
        $response->header->statusCode = 200;
        $response->body = '{ "status": "ok" }';
        $this->gatewayClient_double->send(Argument::type('Array'))->willReturn($response);
        $eMerchantProvider = new eMerchantPaymentProvider(new eMerchantConfig([],''),$this->gatewayClient_double->reveal());
        $actual = $eMerchantProvider->charge(new PaymentProviderDTO(new UserDTO($user), $order, $amount,$card));
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
        $amount = new Money(10000, new Currency('EUR'));
        $card = CreditCardMother::aValidCreditCard();
        $user = UserMother::aJustRegisteredUser()->build();
        $order = OrderMother::aJustOrder()->buildANewWay();
        $response = new Response();
        $response->header->statusCode = 200;
        $response->body = '{ "status": "ko" }';
        $this->gatewayClient_double->send(Argument::type('Array'))->willReturn($response);
        $eMerchantProvider = new eMerchantPaymentProvider(new eMerchantConfig([],''),$this->gatewayClient_double->reveal());
        $actual = $eMerchantProvider->charge(new PaymentProviderDTO(new UserDTO($user), $order, $amount,$card));
        $this->assertEquals($expected,$actual);
    }
}
