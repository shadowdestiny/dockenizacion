<?php
error_reporting(E_ALL);

use EuroMillions\powerball\bootstrap\PowerBallBootstrap;

$app_path = __DIR__.'/';
$global_config_path = $app_path.'../../global_config/';
$tests_path = $app_path.'../../tests/';
$config_path = $app_path . '../shared/config/';
$config_path_web = $app_path . '../web/config';

require_once $app_path.'../../vendor/autoload.php';

try {

    $bootstrap = new EuroMillions\shared\config\bootstrap\Bootstrap(new PowerBallBootstrap(
        $argv, $config_path
    ));
    $bootstrap->execute();
} catch (\Phalcon\Exception $e) {
    echo "PhalconException: ", $e->getMessage(), " at file ", $e->getFile(), " line ", $e->getLine();
    echo "\n", $e->getTraceAsString(), "\n";
    exit(255);
}