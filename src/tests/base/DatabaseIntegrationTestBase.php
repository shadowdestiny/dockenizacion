<?php
/**
 * Created by PhpStorm.
 * User: Euromillions
 * Date: 15/04/15
 * Time: 10:22
 */

namespace tests\base;

use Doctrine\ORM\EntityManager;
use Phalcon\DI;
use PHPUnit_Extensions_Database_DataSet_ArrayDataSet;
use PHPUnit_Extensions_Database_DB_IDatabaseConnection;

abstract class DatabaseIntegrationTestBase extends \PHPUnit_Extensions_Database_TestCase
{
    use PhalconDiRelatedTest;

    const ENTITIES_NS = '\EuroMillions\entities\\';

    protected $connection;
    protected $pdo;
    /** @var  TestBaseHelper */
    protected $helper;
    /** @var  EntityManager */
    protected $entityManager;
    /**
     * Returns the test database connection.
     *
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    protected function getConnection()
    {
        if (empty($this->connection)) {
            $config = DI::getDefault()->get('config');
            $pdo = new \PDO("mysql:dbname={$config->database->dbname};host={$config->database->host}", $config->database->username, $config->database->password);
            $this->connection = $this->createDefaultDBConnection($pdo, $config->database->dbname);
        }
        return $this->connection;
    }
    /**
     * Returns the test dataset.
     *
     * @return PHPUnit_Extensions_Database_DataSet_ArrayDataSet
     */
    protected function getDataSet()
    {
        $fixtures = $this->loadFixturesFromFiles($this->getFixtures());
        $fixtures_to_load = array();
        foreach ($fixtures as $table => $data) {
            $fixtures_to_load[$table] = $data;
        }
        return new PHPUnit_Extensions_Database_DataSet_ArrayDataSet($fixtures_to_load);
    }
    protected function getPDO()
    {
        if (empty($this->pdo)) {
            $this->pdo = $this->getConnection()->getConnection();
        }
        return $this->pdo;
    }
    protected function setUp()
    {
        $this->helper = new TestBaseHelper();
        $conn = $this->getPDO();
        $conn->query("set foreign_key_checks=0");
        parent::setUp();
        $conn->query("set foreign_key_checks=1");
        $this->entityManager = Di::getDefault()->get('entityManager');
    }
    protected function tearDown()
    {
        parent::tearDown();
        $conn = $this->getPDO();
        if ($conn->inTransaction()) {
            $conn->rollBack();
            throw new \LogicException("Transaction opened and not closed");
        }
    }
    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    abstract protected function getFixtures();

    protected function loadFixturesFromFiles($fixtures)
    {
        $result = array();
        foreach ($fixtures as $fixture_file) {
            $fixture_content = include(TESTS_PATH . '/integration/fixtures/' . $fixture_file . '.php');
            foreach ($fixture_content as $table => $values)
                $result[$table] = $values;
        }
        return $result;
    }

    protected function getClassOfArrayElements(array $objects)
    {
        $class = get_class($objects[0]);
        $num_objects = count($objects);
        for ($i = 1; $i < $num_objects; $i++) {
            if (get_class($objects[$i]) != $class) {
                $this->fail();
                return;
            }
        }
        return $class;
    }


}