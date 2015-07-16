<?php
namespace EuroMillions\config\bootstrap;

use EuroMillions\components\EnvironmentDetector;
use Phalcon;
use Phalcon\Config\Adapter\Ini;
use Phalcon\Di;
use tests\base\UnitTestBase;

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
            $utbase = new UnitTestBase();
            return $utbase->getEntityManagerStub()->reveal();
        }
    }

    protected function configLanguage(Di $di)
    {
        if (!$this->isUnitTest) {
            return parent::configLanguage($di);
        } else {
            return 'en';
        }
    }

    protected function getConfigFileName(EnvironmentDetector $em)
    {
        return $em->get().'_test_config.ini';
    }
}