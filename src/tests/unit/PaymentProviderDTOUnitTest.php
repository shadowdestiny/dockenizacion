<?php


namespace tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\CreditCardMother;
use EuroMillions\tests\helpers\mothers\OrderMother;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\vo\dto\PaymentProviderDTO;
use EuroMillions\web\vo\dto\UserDTO;
use Money\Currency;
use Money\Money;

class PaymentProviderDTOUnitTest extends UnitTestBase
{

    private $amount;
    private $card;
    private $user;
    private $order;

    public function setUp()
    {
        $this->amount = new Money(10000, new Currency('EUR'));
        $this->card = CreditCardMother::aValidCreditCard();
        $this->user = UserMother::aJustRegisteredUser()->build();
        $this->order = OrderMother::aJustOrder()->buildANewWay();

        parent::setUp();
    }

    /**
    * method toArray
    * when called
    * should returnArrayWithProperData
    */
    public function test_toArray_called_returnArrayWithProperData()
    {
        $sut = new PaymentProviderDTO(new UserDTO($this->user), $this->order, $this->amount, $this->card);
        $actual = $sut->toArray();

        $this->assertInternalType('array', $actual);

        foreach($this->getExpectedKeys() as $key){
            $this->assertArrayHasKey($key, $actual);
            $this->assertNotNull($actual[$key], $actual);
        }
    }

    /**
    * method jsonSerialize
    * when called
    * should returnSerializableJsonData
    */
    public function test_jsonSerialize_called_returnSerializableJsonData()
    {
        $sut = new PaymentProviderDTO(new UserDTO($this->user), $this->order, $this->amount, $this->card);
        $actual = $sut->jsonSerialize();

        $this->isJson(json_encode($actual));
    }

    private function getExpectedKeys()
    {
        return [
            'idTransaction',
            'userId',
            'userEmail',
            'userIp',
            'amount',
            'currency',
            'creditCardNumber',
            'cvv',
            'expirationYear',
            'expirationMonth',
            'cardHolderName',
        ];
    }
}