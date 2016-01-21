<?php
namespace tests\functional;

use tests\base\DatabaseIntegrationTestBase;

class PayXpertCardPaymentProviderFunctionalTest extends DatabaseIntegrationTestBase
{
    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    protected function getFixtures()
    {
        return [];
    }

    /**
     * method charge
     * when calledWithValidCreditCard
     * should returnProperResponse
     */
    public function test_charge_calledWithValidCreditCard_returnProperResponse()
    {

    }

}