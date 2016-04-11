<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
use EuroMillions\shared\config\bootstrap\Bootstrap;

$public_path = __DIR__;
$app_web_path = $public_path . '/../apps/web';
$app_path = $public_path . '/../apps/';
$global_config_path = $app_path . '../global_config/';
define('APP_PATH', $app_path);

$config_path = $app_path . 'shared/config/';
$tests_path = $public_path . '/../tests/';

require_once __DIR__.'/../vendor/autoload.php';

try {
    $bootstrap = new Bootstrap(new \EuroMillions\shared\config\bootstrap\WebBootstrapStrategy(
        $app_path, $config_path, $app_path . 'web/assets/', $tests_path, 'config.ini'
    ));
    $bootstrap->execute();
} catch (\Exception $e) {
    echo "PhalconException: ", $e->getMessage(), " at file ", $e->getFile(), " line ", $e->getLine();
    echo "<br><pre>", var_dump($e->getTrace()), "</pre>";
}