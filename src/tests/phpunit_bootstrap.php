<?php
define('TESTS_PATH', __DIR__.'/');
define('APP_PATH', TESTS_PATH . '../apps/');
require_once APP_PATH.'../vendor/autoload.php';
require_once(APP_PATH . 'shared/config/bootstrap/WebLoader.php');

$loader = new \EuroMillions\shared\config\bootstrap\WebLoader(APP_PATH, TESTS_PATH);
$loader->register();
