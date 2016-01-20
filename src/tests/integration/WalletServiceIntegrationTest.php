<?php


namespace tests\integration;


use tests\base\DatabaseIntegrationTestBase;

class WalletServiceIntegrationTest extends DatabaseIntegrationTestBase
{

    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    protected function getFixtures()
    {
        return [
            'users',
        ];
    }

    /**
     * method rechargeWithCreditCard
     * when chargeIsAllowed
     * should persistProperAmountInUserWallet
     */
    public function test_rechargeWithCreditCard_chargeIsAllowed_persistProperAmountInUserWallet()
    {
        $this->markTestIncomplete();
    }
}