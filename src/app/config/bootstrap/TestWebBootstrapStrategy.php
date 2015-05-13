<?php
namespace app\config\bootstrap;

use app\components\EnvironmentDetector;
use Phalcon;
use Phalcon\Config\Adapter\Ini;
use Phalcon\Di;

class TestWebBootstrapStrategy extends WebBootstrapStrategy
{
    protected $isUnitTest;

    public function __construct($isUnitTest, $appPath, $globalConfigPath, $configPath, $assetsPath, $testsPath)
    {
        $this->isUnitTest = $isUnitTest;
        parent::__construct($appPath, $globalConfigPath, $configPath, $assetsPath, $testsPath);
    }

    public function execute(Di $di)
    {
        DI::reset();
        DI::setDefault($di);
    }

    protected function configDoctrine(Ini $appConfig)
    {
        if (!$this->isUnitTest) {
            return parent::configDoctrine($appConfig);
        } else {
            return null;
        }
    }

    protected function getConfigFileName(EnvironmentDetector $em)
    {
        return $em->get().'_test_config.ini';
    }

}