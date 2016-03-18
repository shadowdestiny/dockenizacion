<?php
global $test_suite;
use EuroMillions\shared\config\bootstrap\Bootstrap;
use EuroMillions\shared\config\bootstrap\TestWebBootstrapStrategy;

$is_unit_test = ($test_suite === 'unit');

$bootstrap = new Bootstrap(new TestWebBootstrapStrategy($is_unit_test, APP_PATH, APP_PATH . '../global_config/', APP_PATH . 'shared/config/', APP_PATH . 'web/assets/', TESTS_PATH));
return $bootstrap->execute();