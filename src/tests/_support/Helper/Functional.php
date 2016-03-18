<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\TestCase;

class Functional extends \Codeception\Module
{
    public function _before(TestCase $test)
    {
        $command = '/vagrant/dev-scripts/run_seed.sh devel-test';
        exec($command);
    }
}
