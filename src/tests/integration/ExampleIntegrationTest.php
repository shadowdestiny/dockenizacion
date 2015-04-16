<?php
namespace tests\integration;

use tests\base\IntegrationTestBase;

class UsersQueryDAOTest extends IntegrationTestBase
{
    protected function getFixtures()
    {
        return [
            'translations'
        ];
    }

    public function test_integration_tests_are_working()
    {
        $conn = $this->getPDO();
        $result = $conn->query("SELECT * FROM translations ORDER BY translation_id ASC LIMIT 1");
        $result->setFetchMode(\PDO::FETCH_ASSOC);
        $actual = $result->fetch();
        $this->assertEquals(["translation_id" => 1, "key" => "key1", "used" => 1], $actual);
    }
}