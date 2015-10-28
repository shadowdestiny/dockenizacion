<?php
$test_path = __DIR__;
$app_path = $test_path . '/../apps';
require_once($app_path . '../vendor/autoload.php');
require_once($app_path . 'shareconfig/bootstrap/WebLoader.php');

$loader = new \EuroMillions\shareconfig\bootstrap\WebLoader($app_path, $test_path);
$loader->register();
