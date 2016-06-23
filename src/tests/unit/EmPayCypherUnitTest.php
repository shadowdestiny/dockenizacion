<?php


namespace EuroMillions\tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\vo\EmPayCypher;

class EmPayCypherUnitTest extends UnitTestBase
{


    /**
     * method getQueryString
     * when called
     * should returnQueryCyphered
     */
    public function test_getQueryString_called_returnQueryCyphered()
    {
        $params = [
            'mobile' => 0,
            'order_reference' => '123456',
            'order_currency' => 'EUR',
            'amount' => '5.00',
            'qty' => '1',
            'form' => 'purchase'
        ];
        $config = [
            'client_id' => 815623,
            'form_purchase_id' => 2883,
            'test_transaction' => 1,
        ];
        $md5Key = '3TgJ2CqehZ';

        $sut = new EmPayCypher($params,$config,$md5Key);
        $actual = $sut->getQueryString();
        $this->assertNotNull($actual);
    }


}