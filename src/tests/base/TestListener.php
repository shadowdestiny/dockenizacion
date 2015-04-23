<?php
namespace tests\base;

use app\config\bootstrap\Bootstrap;
use app\config\bootstrap\TestWebBootstrapStrategy;
use Phalcon\DI;
use PHPUnit_Framework_TestSuite;

class TestListener extends \PHPUnit_Framework_BaseTestListener
{
    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        //EMTD refactorizar a un patrón strategy
        if (getenv('TEST_ENV') == 'shippable') {
            $env = 'shippable';
            $config_file = 'shippableconfig.ini';
        } else {
            $env = 'local';
            $config_file = 'testconfig.ini';
        }

        if ($suite->getName() == "integration") {
            $config = DI::getDefault()->get('config');
            $command = "mysql -h {$config->database->host} -u {$config->database->username} -p{$config->database->password} -e 'CREATE DATABASE IF NOT EXISTS {$config->database->dbname};' 2>/dev/null";
            exec($command);
            $command = "mysqldump -h {$config->database->host} -u {$config->database->username} -p{$config->database->password} -d {$config->database->original_db_name} 2>/dev/null | mysql -h {$config->database->host} -u {$config->database->username} -p{$config->database->password} -D{$config->database->dbname} 2>/dev/null";
            exec($command);
            $bootstrap = new Bootstrap(new TestWebBootstrapStrategy(false, APP_PATH,APP_PATH . 'config/'  , APP_PATH . 'assets/', TESTS_PATH . '/../', $config_file));
        } else {
            $bootstrap = new Bootstrap(new TestWebBootstrapStrategy(true, APP_PATH, APP_PATH . 'config/' , APP_PATH . 'assets/', TESTS_PATH . '/../', $config_file));
        }
        $bootstrap->execute();
    }
}