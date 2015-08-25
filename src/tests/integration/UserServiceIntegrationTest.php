<?php
namespace tests\integration;

use EuroMillions\vo\UserId;
use tests\base\DatabaseIntegrationTestBase;

class UserServiceIntegrationTest extends DatabaseIntegrationTestBase
{

    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    protected function getFixtures()
    {
        return [
            'users',
            'languages',
        ];
    }

    /**
     * method getBalance
     * when called
     * should returnProperBalance
     * @dataProvider getUserIdsAndExpectedBalances
     * @param $uuid
     * @param $expected
     */
    public function test_getBalance_called_returnProperBalance($uuid, $expected)
    {
        $userId = new UserId($uuid);
        $dsf = $this->getDomainServiceFactory();
        $sut = $dsf->getUserService();
        $actual = $sut->getBalance($userId);
        $this->assertEquals($expected, $actual);
    }

    public function getUserIdsAndExpectedBalances()
    {
        return [
            ['9098299B-14AC-4124-8DB0-19571EDABE55', '€3,000.05'],
        ];
    }
}