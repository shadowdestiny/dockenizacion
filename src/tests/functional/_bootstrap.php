<?php
global $test_suite;
$test_suite = 'functional';

use EuroMillions\shared\components\EnvironmentDetector;
use Phalcon\Di;

include __DIR__.'/../../public/index-test.php';

$di = DI::getDefault();
$config = $di->get('config');
/** @var EnvironmentDetector $ed */
$environment = $di->get('environmentDetector')->get();

$command = '/vagrant/dev-scripts/schema_dump.sh';
exec($command);