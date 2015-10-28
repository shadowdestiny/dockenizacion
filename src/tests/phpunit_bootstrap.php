<?php
define('TESTS_PATH', __DIR__.'/');
define('APP_PATH', TESTS_PATH.'../apps/');
require_once TESTS_PATH.'../vendor/autoload.php';
require_once(TESTS_PATH.'../apps/shareconfig/bootstrap/WebLoader.php');

$loader = new \EuroMillions\shareconfig\bootstrap\WebLoader(APP_PATH, TESTS_PATH);
$loader->register();
