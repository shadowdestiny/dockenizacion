<?php
error_reporting(E_ALL);

use EuroMillions\shared\config\bootstrap\CliLoader;
use EuroMillions\shared\config\bootstrap\CliBootstrapStrategy;


$app_path = __DIR__.'/';
$global_config_path = $app_path.'../global_config/';
$tests_path = $app_path.'../tests/';
$config_path = $app_path . 'shared/config/';
$config_path_web = $app_path . 'web/config';

require_once $app_path.'../vendor/autoload.php';
require_once($config_path.'bootstrap/CliLoader.php');
try {
    $loader = new CliLoader($app_path, $tests_path);
    $loader->register();

    $bootstrap = new \EuroMillions\shared\config\bootstrap\Bootstrap(new CliBootstrapStrategy(
        $argv, $global_config_path , $config_path, 'cliconfig.ini'
    ));
    $bootstrap->execute();
} catch (\Phalcon\Exception $e) {
    echo "PhalconException: ", $e->getMessage(), " at file ", $e->getFile(), " line ", $e->getLine();
    echo "\n", $e->getTraceAsString(), "\n";
    exit(255);
}