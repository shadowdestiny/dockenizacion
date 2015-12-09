<?php
define('TESTS_PATH', __DIR__.'/');
define('APP_PATH', TESTS_PATH . '../apps/');
require_once APP_PATH.'../vendor/autoload.php';
require_once(APP_PATH . 'shared/shareconfig/bootstrap/WebLoader.php');

$loader = new \EuroMillions\shared\shareconfig\bootstrap\WebLoader(APP_PATH, TESTS_PATH);
$loader->register();
