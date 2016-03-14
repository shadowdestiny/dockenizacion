<?php
use EuroMillions\shared\components\EnvironmentDetector;
use EuroMillions\shared\config\bootstrap\Bootstrap;
use EuroMillions\shared\config\bootstrap\TestWebBootstrapStrategy;
use Phalcon\Di;

$bootstrap = new Bootstrap(new TestWebBootstrapStrategy(false, APP_PATH, APP_PATH . '../global_config/', APP_PATH . 'shared/config/', APP_PATH . 'assets/', TESTS_PATH));
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