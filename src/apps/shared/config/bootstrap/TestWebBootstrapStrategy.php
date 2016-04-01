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
use Redis;

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
        Di::reset();
        Di::setDefault($di);
        $app = new Phalcon\Mvc\Application($di);
        $this->configureModules($app);
        return $app;
    }

    /**
     * @param Ini $appConfig
     * @return EntityManager
     */
    protected function configDoctrine(Ini $appConfig, Redis $redis)
    {
        if (!$this->isUnitTest) {
            return parent::configDoctrine($appConfig, $redis);
        } else {
            $utbase = new \EuroMillions\tests\base\UnitTestBase();
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