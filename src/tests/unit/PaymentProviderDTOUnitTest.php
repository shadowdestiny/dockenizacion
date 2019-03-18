<?php


namespace tests\unit;


use EuroMillions\shared\components\builder\PaymentProviderDTOBuilder;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\CreditCardMother;
use EuroMillions\tests\helpers\mothers\OrderMother;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\services\card_payment_providers\emerchant\eMerchantConfig;
use EuroMillions\web\services\card_payment_providers\eMerchantPaymentProvider;
use EuroMillions\web\services\card_payment_providers\FakeCardPaymentProvider;
use EuroMillions\web\services\card_payment_providers\payxpert\PayXpertConfig;
use EuroMillions\web\services\card_payment_providers\PayXpertCardPaymentProvider;
use EuroMillions\web\services\card_payment_providers\royalpay\RoyalPayConfig;
use EuroMillions\web\services\card_payment_providers\RoyalPayPaymentProvider;
use EuroMillions\web\services\card_payment_providers\widecard\WideCardConfig;
use EuroMillions\web\services\card_payment_providers\WideCardPaymentProvider;
use EuroMillions\web\vo\dto\UserDTO;
use Money\Currency;
use Money\Money;

class PaymentProviderDTOUnitTest extends UnitTestBase
{

    private $amount;
    private $card;
    private $user;
    private $order;

    private $wirecard_gatewayClient_double;
    private $royalpay_gatewayClient_double;
    private $payxpert_gatewayClient_double;
    private $emerchant_gatewayClient_double;

    public function setUp()
    {
        parent::setUp();

        $this->amount = new Money(10000, new Currency('EUR'));
        $this->card = CreditCardMother::aValidCreditCard();
        $this->user = UserMother::aJustRegisteredUser()->build();
        $this->order = OrderMother::aJustOrder()->buildANewWay();

        $this->wirecard_gatewayClient_double = $this->prophesize('EuroMillions\web\services\card_payment_providers\widecard\GatewayClientWrapper');
        $this->royalpay_gatewayClient_double = $this->prophesize('EuroMillions\web\services\card_payment_providers\royalpay\GatewayClientWrapper');
        $this->payxpert_gatewayClient_double = $this->prophesize('EuroMillions\web\services\card_payment_providers\payxpert\GatewayClientWrapper');
        $this->emerchant_gatewayClient_double = $this->prophesize('EuroMillions\web\services\card_payment_providers\emerchant\GatewayClientWrapper');
    }

    /**
     * method wirecard_toArray
     * when called
     * should returnArrayWithProperData
     * @dataProvider wirecardArray
     * @param $value
     */
    public function test_wirecard_toArray_called_returnArrayWithProperData($value)
    {
        $provider = new WideCardPaymentProvider(new WideCardConfig([],''), $this->wirecard_gatewayClient_double->reveal());
        $sut = (new PaymentProviderDTOBuilder())
         ->setProvider($provider)
         ->setUser(new UserDTO($this->user))
         ->setOrder($this->order)
         ->setAmount($this->amount)
         ->setCard($this->card)
         ->build()
        ;

        $actual = $sut->toArray();

        $this->assertInternalType('array', $actual);
        $this->assertArrayHasKey($value, $actual);
        $this->assertNotNull($actual[$value], $actual);
    }

    /**
    * method wirecard_jsonSerialize
    * when called
    * should returnSerializableJsonData
    */
    public function test_wirecard_jsonSerialize_called_returnSerializableJsonData()
    {

        $provider = new WideCardPaymentProvider(new WideCardConfig([],''), $this->wirecard_gatewayClient_double->reveal());
        $sut = (new PaymentProviderDTOBuilder())
            ->setProvider($provider)
            ->setUser(new UserDTO($this->user))
            ->setOrder($this->order)
            ->setAmount($this->amount)
            ->setCard($this->card)
            ->build()
        ;

        $actual = $sut->jsonSerialize();

        $this->isJson(json_encode($actual));
    }

    /**
     * method royalpay_toArray
     * when called
     * should returnArrayWithProperData
     * @dataProvider royalpayArray
     * @param $value
     */
    public function test_royalpay_toArray_called_returnArrayWithProperData($value)
    {
        $provider = new RoyalPayPaymentProvider(new RoyalPayConfig([],''), $this->royalpay_gatewayClient_double->reveal());
        $sut = (new PaymentProviderDTOBuilder())
            ->setProvider($provider)
            ->setUser(new UserDTO($this->user))
            ->setOrder($this->order)
            ->setAmount($this->amount)
            ->setCard($this->card)
            ->build()
        ;

        $actual = $sut->toArray();

        $this->assertInternalType('array', $actual);
        $this->assertArrayHasKey($value, $actual);
        $this->assertNotNull($actual[$value], $actual);
    }

    /**
     * method royalpay_jsonSerialize
     * when called
     * should returnSerializableJsonData
     */
    public function test_royalpay_jsonSerialize_called_returnSerializableJsonData()
    {

        $provider = new RoyalPayPaymentProvider(new RoyalPayConfig([],''), $this->royalpay_gatewayClient_double->reveal());
        $sut = (new PaymentProviderDTOBuilder())
            ->setProvider($provider)
            ->setUser(new UserDTO($this->user))
            ->setOrder($this->order)
            ->setAmount($this->amount)
            ->setCard($this->card)
            ->build()
        ;

        $actual = $sut->jsonSerialize();

        $this->isJson(json_encode($actual));
    }

    /**
     * method payxpert_toArray
     * when called
     * should returnArrayWithProperData
     * @dataProvider payxpertArray
     * @param $value
     */
    public function test_payxpert_toArray_called_returnArrayWithProperData($value)
    {
        $provider = new PayXpertCardPaymentProvider(new PayXpertConfig('','',''), $this->payxpert_gatewayClient_double->reveal());
        $sut = (new PaymentProviderDTOBuilder())
            ->setProvider($provider)
            ->setUser(new UserDTO($this->user))
            ->setOrder($this->order)
            ->setAmount($this->amount)
            ->setCard($this->card)
            ->build()
        ;

        $actual = $sut->toArray();

        $this->assertInternalType('array', $actual);
        $this->assertArrayHasKey($value, $actual);
        $this->assertNotNull($actual[$value], $actual);
    }

    /**
     * method payxpert_jsonSerialize
     * when called
     * should returnSerializableJsonData
     */
    public function test_payxpert_jsonSerialize_called_returnSerializableJsonData()
    {

        $provider = new PayXpertCardPaymentProvider(new PayXpertConfig('','',''), $this->payxpert_gatewayClient_double->reveal());
        $sut = (new PaymentProviderDTOBuilder())
            ->setProvider($provider)
            ->setUser(new UserDTO($this->user))
            ->setOrder($this->order)
            ->setAmount($this->amount)
            ->setCard($this->card)
            ->build()
        ;

        $actual = $sut->jsonSerialize();

        $this->isJson(json_encode($actual));
    }

    /**
     * method emerchant_toArray
     * when called
     * should returnArrayWithProperData
     * @dataProvider emerchantArray
     * @param $value
     */
    public function test_emerchant_toArray_called_returnArrayWithProperData($value)
    {
        $provider = new eMerchantPaymentProvider(new eMerchantConfig('',''), $this->emerchant_gatewayClient_double->reveal());
        $sut = (new PaymentProviderDTOBuilder())
            ->setProvider($provider)
            ->setUser(new UserDTO($this->user))
            ->setOrder($this->order)
            ->setAmount($this->amount)
            ->setCard($this->card)
            ->build()
        ;

        $actual = $sut->toArray();

        $this->assertInternalType('array', $actual);
        $this->assertArrayHasKey($value, $actual);
        $this->assertNotNull($actual[$value], $actual);
    }

    /**
     * method emerchant_jsonSerialize
     * when called
     * should returnSerializableJsonData
     */
    public function test_emerchant_jsonSerialize_called_returnSerializableJsonData()
    {

        $provider = new eMerchantPaymentProvider(new eMerchantConfig('',''), $this->emerchant_gatewayClient_double->reveal());
        $sut = (new PaymentProviderDTOBuilder())
            ->setProvider($provider)
            ->setUser(new UserDTO($this->user))
            ->setOrder($this->order)
            ->setAmount($this->amount)
            ->setCard($this->card)
            ->build()
        ;

        $actual = $sut->jsonSerialize();

        $this->isJson(json_encode($actual));
    }

    /**
     * method fakecard_toArray
     * when called
     * should returnArrayWithProperData
     * @dataProvider fakecardArray
     * @param $value
     */
    public function test_fakecard_toArray_called_returnArrayWithProperData($value)
    {
        $provider = new FakeCardPaymentProvider();
        $sut = (new PaymentProviderDTOBuilder())
            ->setProvider($provider)
            ->setUser(new UserDTO($this->user))
            ->setOrder($this->order)
            ->setAmount($this->amount)
            ->setCard($this->card)
            ->build()
        ;

        $actual = $sut->toArray();

        $this->assertInternalType('array', $actual);
        $this->assertArrayHasKey($value, $actual);
        $this->assertNotNull($actual[$value], $actual);
    }

    /**
     * method fakecard_jsonSerialize
     * when called
     * should returnSerializableJsonData
     */
    public function test_fakecard_jsonSerialize_called_returnSerializableJsonData()
    {

        $provider = new FakeCardPaymentProvider();
        $sut = (new PaymentProviderDTOBuilder())
            ->setProvider($provider)
            ->setUser(new UserDTO($this->user))
            ->setOrder($this->order)
            ->setAmount($this->amount)
            ->setCard($this->card)
            ->build()
        ;

        $actual = $sut->jsonSerialize();

        $this->isJson(json_encode($actual));
    }

    public function wirecardArray()
    {
        return [
            ['idTransaction'],
            ['userID'],
            ['amount'],
            ['creditCardNumber'],
            ['cvc'],
            ['expirationYear'],
            ['expirationMonth'],
            ['cardHolderName'],
        ];
    }

    public function royalpayArray()
    {
        return [
            ['orderID'],
            ['userID'],
            ['amount'],
            ['currency'],
            ['CallbackUrl'],
            ['SuccessUrl'],
            ['FailUrl'],
            ['PendingUrl'],
            ['cardNumber'],
            ['cardCvv'],
            ['cardYear'],
            ['cardMonth'],
            ['cardHolderName'],
        ];
    }

    public function payxpertArray()
    {
        return [
            ['amount'],
            ['creditCardNumber'],
            ['cvv'],
            ['cardHolderName'],
            ['expirationMonth'],
            ['expirationYear'],
        ];
    }

    public function emerchantArray()
    {
        return [
            ['idTransaction'],
            ['amount'],
            ['creditCardNumber'],
            ['cvc'],
            ['expirationYear'],
            ['expirationMonth'],
            ['cardHolderName'],
            ['email'],
            ['ip'],
        ];
    }

    public function fakeCardArray()
    {
        return [
            ['creditCardNumber'],
            ['cvv'],
            ['expirationYear'],
            ['expirationMonth'],
            ['cardHolderName'],
        ];
    }
}