<?php
namespace EuroMillions\tests\base;

use EuroMillions\shared\config\bootstrap\Bootstrap;
use EuroMillions\shared\config\bootstrap\TestWebBootstrapStrategy;
use Phalcon\DI;
use PHPUnit_Framework_TestSuite;
use EuroMillions\shared\components\EnvironmentDetector;

class TestListener extends \PHPUnit_Framework_BaseTestListener
{
    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        if (
            strpos($suite->getName(),"tests\\integration") !== false ||
            strpos($suite->getName(),"tests\\functional") !== false
        ) {
            $bootstrap = new Bootstrap(new TestWebBootstrapStrategy(false, APP_PATH, APP_PATH . 'shared/config/', APP_PATH . 'assets/', TESTS_PATH));
            $di = Di::getDefault();
            $config = $di->get('config');
            /** @var EnvironmentDetector $ed */
            $environment = $di->get('environmentDetector')->get();

            if ($environment === 'test') {
                $command = '/var/www/vendor/bin/phinx migrate --configuration="phinx.yml" -e test';
                exec($command);

                /*
                 * This command tries to copy the original DB to the test DB. In a docker environment we use only a ONE empty database for now.
                 *
                 */
                //$command = "mysqldump -h {$config->database->host} -u {$config->database->username} -p{$config->database->password} -d {$config->database->original_db_name} 2>/dev/null | mysql -h {$config->database->host} -u {$config->database->username} -p{$config->database->password} -D{$config->database->dbname} 2>/dev/null";
                //exec($command);
            }
        } else {
            $bootstrap = new Bootstrap(new TestWebBootstrapStrategy(true, APP_PATH, APP_PATH . 'shared/config/', APP_PATH . 'assets/', TESTS_PATH));
        }
        $bootstrap->execute();
    }
}