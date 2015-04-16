<?php
error_reporting(E_ALL);

use app\config\bootstrap\CliLoader;
use app\config\bootstrap\CliBootstrapStrategy;

$app_path = __DIR__.'/';
$tests_path = $app_path.'../tests/';
$config_path = $app_path . 'config/';
require_once $app_path.'../vendor/autoload.php';
require_once($config_path.'bootstrap/CliLoader.php');
try {
    $loader = new CliLoader($app_path, $tests_path);
    $loader->register();

    $bootstrap = new \app\config\bootstrap\Bootstrap(new CliBootstrapStrategy(
        $argv,
        $config_path,
        'cliconfig.ini'
    ));
    $bootstrap->execute();
} catch (\Phalcon\Exception $e) {
    echo "PhalconException: ", $e->getMessage(), " at file ", $e->getFile(), " line ", $e->getLine();
    echo "\n", $e->getTraceAsString(), "\n";
    exit(255);
}