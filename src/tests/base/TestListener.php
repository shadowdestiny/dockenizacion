<?php
namespace tests\base;

use app\config\bootstrap\Bootstrap;
use app\config\bootstrap\TestWebBootstrapStrategy;
use Phalcon\DI;
use PHPUnit_Framework_TestSuite;
use app\components\EnvironmentDetector;

class TestListener extends \PHPUnit_Framework_BaseTestListener
{
    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        if ($suite->getName() == "integration") {
            $bootstrap = new Bootstrap(new TestWebBootstrapStrategy(false, APP_PATH, APP_PATH.'../global_config/', APP_PATH . 'config/', APP_PATH . 'assets/', TESTS_PATH . '/../'));
            $di = DI::getDefault();
            $config = $di->get('config');
            /** @var EnvironmentDetector $ed */
            $environment = $di->get('environmentDetector')->get();


            if ($environment == 'vagrant' ) {
                $command = "mysql -h {$config->database->host} -u {$config->database->username} -p{$config->database->password} -e 'CREATE DATABASE IF NOT EXISTS {$config->database->dbname};' 2>/dev/null";
                exec($command);
                $command = "mysqldump -h {$config->database->host} -u {$config->database->username} -p{$config->database->password} -d {$config->database->original_db_name} 2>/dev/null | mysql -h {$config->database->host} -u {$config->database->username} -p{$config->database->password} -D{$config->database->dbname} 2>/dev/null";
                exec($command);
            }
        } else {
            $bootstrap = new Bootstrap(new TestWebBootstrapStrategy(true, APP_PATH, APP_PATH.'../global_config/', APP_PATH . 'config/', APP_PATH . 'assets/', TESTS_PATH . '/../'));
        }
        $bootstrap->execute();
    }
}