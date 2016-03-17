<?php
use EuroMillions\shared\config\bootstrap\Bootstrap;
use EuroMillions\shared\config\bootstrap\TestWebBootstrapStrategy;

$is_unit_test = (TEST_SUITE === 'unit');

$bootstrap = new Bootstrap(new TestWebBootstrapStrategy($is_unit_test, APP_PATH, APP_PATH . '../global_config/', APP_PATH . 'shared/config/', APP_PATH . 'assets/', TESTS_PATH));
return $bootstrap->execute();