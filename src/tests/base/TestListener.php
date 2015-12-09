<?php
namespace tests\base;

use EuroMillions\shared\shareconfig\bootstrap\Bootstrap;
use EuroMillions\shared\shareconfig\bootstrap\TestWebBootstrapStrategy;
use Phalcon\DI;
use PHPUnit_Framework_TestSuite;
use EuroMillions\shared\sharecomponents\EnvironmentDetector;

class TestListener extends \PHPUnit_Framework_BaseTestListener
{
    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        if (
            strpos($suite->getName(),"tests\\integration") !== false ||
            strpos($suite->getName(),"tests\\functional") !== false
        ) {
            $bootstrap = new Bootstrap(new TestWebBootstrapStrategy(false, APP_PATH, APP_PATH . '../global_config/', APP_PATH . 'shared/shareconfig/', APP_PATH . 'assets/', TESTS_PATH));
            $di = DI::getDefault();
            $config = $di->get('config');
            /** @var EnvironmentDetector $ed */
            $environment = $di->get('environmentDetector')->get();

            if ($environment === 'vagrant') {
                $command = "mysqldump -h {$config->database->host} -u {$config->database->username} -p{$config->database->password} -d {$config->database->original_db_name} 2>/dev/null | mysql -h {$config->database->host} -u {$config->database->username} -p{$config->database->password} -D{$config->database->dbname} 2>/dev/null";
                exec($command);
                $command = '/vagrant/dev-scripts/schema_migration.sh dev';
                exec($command);
            }
        } else {
            $bootstrap = new Bootstrap(new TestWebBootstrapStrategy(true, APP_PATH, APP_PATH.'../global_config/', APP_PATH . 'shared/shareconfig/', APP_PATH . 'assets/', TESTS_PATH));
        }
        $bootstrap->execute();
    }
}