<?php
define('TESTS_PATH', __DIR__);
define('APP_PATH', TESTS_PATH . '/../app/');
require_once APP_PATH.'../vendor/autoload.php';
require_once(APP_PATH . 'config/bootstrap/WebLoader.php');
$loader = new \app\config\bootstrap\WebLoader(APP_PATH, TESTS_PATH.'/');
$loader->register();
