<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\TestCase;
use Phalcon\Di;

class Functional extends \Codeception\Module
{
    public function _before(TestCase $test)
    {
        $di = DI::getDefault();
        $environment = $di->get('environmentDetector')->get();
        $command = '/vagrant/dev-scripts/run_seed.sh '.$environment;
        exec($command);
    }
}
