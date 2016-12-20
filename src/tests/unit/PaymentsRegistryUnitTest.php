<?php


namespace EuroMillions\tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\services\card_payment_providers\payments_util\PaymentsRegistry;

class PaymentsRegistryUnitTest extends UnitTestBase
{

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * method getInstances
     * when called
     * should returnArrayWithInstances
     */
    public function test_getInstances_called_returnArrayWithInstances()
    {
        $configArr = ['wirecard','emerchant'];
        $sut = new PaymentsRegistry($configArr);
        $actual = $sut->getInstances();
        $this->assertInstanceOf('EuroMillions\web\services\card_payment_providers\WideCardPaymentStrategy',$actual[0]);
        $this->assertInstanceOf('EuroMillions\web\services\card_payment_providers\WideCardPaymentStrategy',$actual[0]);
    }


}