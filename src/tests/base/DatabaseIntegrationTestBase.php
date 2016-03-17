<?php
/**
 * Created by PhpStorm.
 * User: Euromillions
 * Date: 15/04/15
 * Time: 10:22
 */

namespace EuroMillions\tests\base;

use Doctrine\ORM\EntityManager;
use PDO;
use Phalcon\DI;
use PHPUnit_Extensions_Database_DataSet_ArrayDataSet;
use PHPUnit_Extensions_Database_DB_IDatabaseConnection;

abstract class DatabaseIntegrationTestBase extends \PHPUnit_Extensions_Database_TestCase
{
    use PhalconDiRelatedTest;
    use TestHelperTrait;

    protected $connection;
    protected $pdo;
    /** @var  EntityManager */
    protected $entityManager;
    /** @var  \Redis */
    protected $cache;
    /**
     * Returns the test database connection.
     *
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    protected function getConnection()
    {
        if (empty($this->connection)) {
            $config = DI::getDefault()->get('config');
            $pdo = new \PDO("mysql:dbname={$config->database->dbname};host={$config->database->host};charset=utf8", $config->database->username, $config->database->password);
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
        $conn = $this->getPDO();
        $conn->query("set foreign_key_checks=0");
        parent::setUp();
        $conn->query("set foreign_key_checks=1");
        $this->entityManager = Di::getDefault()->get('entityManager');
        $this->cache = Di::getDefault()->get('redisCache');
    }
    protected function tearDown()
    {
        parent::tearDown();
        $conn = $this->getPDO();
        if ($conn->inTransaction()) {
            $conn->rollBack();
            throw new \LogicException("Transaction opened and not closed");
        }
        $fixtures = $this->getFixtures();
        $conn->exec("set foreign_key_checks=0");
        foreach ($fixtures as $fixture) {
            if ($conn->exec("TRUNCATE $fixture") === false) {
                $this->fail('Couldn\'t truncate table '.$fixture);
            }
        }
        $tables = $conn->query("SELECT DISTINCT TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='euromillions_test';");
        $tables = $tables->fetchAll(PDO::FETCH_COLUMN, 0);
        foreach($tables as $table) {
            if ($table !== 'phinxlog') {
                $count = $conn->query('SELECT * FROM '.$table.' LIMIT 1');
                if ($count->rowCount()) {
                    $this->fail('There are records left on table: '.$table);
                }
            }
        }
        $conn->exec("set foreign_key_checks=1");
        $this->cache->flushAll();
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
            $fixture_content = include(TESTS_PATH . 'integration/fixtures/' . $fixture_file . '.php');
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
                return false;
            }
        }
        return $class;
    }


}