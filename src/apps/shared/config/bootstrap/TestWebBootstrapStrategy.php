<?php
namespace EuroMillions\shared\config\bootstrap;

use Doctrine\ORM\EntityManager;
use EuroMillions\shared\components\EnvironmentDetector;
use EuroMillions\shared\components\PhalconUrlWrapper;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\services\factories\ServiceFactory;
use Phalcon;
use Phalcon\Config\Adapter\Ini;
use Phalcon\Di;

class TestWebBootstrapStrategy extends WebBootstrapStrategy
{
    use BootstrapBehaviourShared;

    protected $isUnitTest;
    protected $testsPath;

    public function __construct($isUnitTest, $appPath, $globalConfigPath, $configPath, $assetsPath, $testsPath)
    {
        $this->testsPath = $testsPath;
        $this->isUnitTest = $isUnitTest;
        parent::__construct($appPath, $globalConfigPath, $configPath, $assetsPath);
    }

    public function dependencyInjector()
    {
        $di = parent::dependencyInjector();
        $di->set('testsPath', function() {return $this->testsPath;}, true);
        $this->shareTheseServices($di);
        return $di;
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
            return $utbase->prophesizeEntityManager()->reveal();
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

    protected function siteConfig(EntityManager $entityManager, $di)
    {
    }

    protected function getConfigFileName(EnvironmentDetector $em)
    {
        return $em->get().'_test_config.ini';
    }

    protected function configDomainServiceFactory(Di $di)
    {
        return new DomainServiceFactory($di, new ServiceFactory($di));
    }

    protected function configUrl()
    {
        $url = new PhalconUrlWrapper();
        $url->setBaseUri('https://localhost');
        return $url;
    }
}