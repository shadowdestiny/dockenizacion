<?php
// Here you can initialize variables that will be available to your tests
use EuroMillions\shared\config\bootstrap\Bootstrap;
use EuroMillions\shared\config\bootstrap\TestWebBootstrapStrategy;

$bootstrap = new Bootstrap(new TestWebBootstrapStrategy(true, APP_PATH, APP_PATH.'../global_config/', APP_PATH . 'shared/config/', APP_PATH . 'assets/', TESTS_PATH));
$bootstrap->execute();