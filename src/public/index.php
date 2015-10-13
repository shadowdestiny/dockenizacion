<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

use EuroMillions\config\bootstrap\WebLoader;
use EuroMillions\config\bootstrap\Bootstrap;

$public_path = __DIR__;
$app_path = $public_path . '/../app/';
$global_config_path = $app_path . '../global_config/';
define('APP_PATH', $app_path);
$config_path = $app_path . 'config/';
$tests_path = $public_path . '/../tests/';
require_once '../vendor/autoload.php';
require_once($config_path . 'bootstrap/WebLoader.php');
try {
    $loader = new WebLoader($app_path, $tests_path);
    $loader->register();

    $bootstrap = new Bootstrap(new \EuroMillions\config\bootstrap\WebBootstrapStrategy(
        $app_path, $global_config_path, $config_path, $app_path . 'assets/', $tests_path, 'config.ini'
    ));
    $bootstrap->execute();
} catch (\Phalcon\Exception $e) {
    echo "PhalconException: ", $e->getMessage(), " at file ", $e->getFile(), " line ", $e->getLine();
    echo "<br><pre>", var_dump($e->getTrace()), "</pre>";
}