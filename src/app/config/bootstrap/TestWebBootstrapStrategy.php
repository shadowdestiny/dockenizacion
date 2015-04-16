<?php
namespace app\config\bootstrap;

use Phalcon;
use Phalcon\Di;

class TestWebBootstrapStrategy extends WebBootstrapStrategy
{
    protected $isUnitTest;

    public function __construct($isUnitTest, $appPath, $configPath, $assetsPath, $testsPath, $configFile)
    {
        $this->isUnitTest = $isUnitTest;
        parent::__construct($appPath, $configPath, $assetsPath, $testsPath, $configFile);
    }

    public function execute(Di $di)
    {
        DI::reset();
        DI::setDefault($di);
    }

    protected function configDb($options)
    {
        if (!$this->isUnitTest) {
            return parent::configDb($options);
        } else {
            return null;
        }

    }

    protected function configDoctrine()
    {
        if (!$this->isUnitTest) {
            return parent::configDoctrine();
        } else {
            return null;
        }
    }
}