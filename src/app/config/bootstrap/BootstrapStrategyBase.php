<?php
namespace EuroMillions\config\bootstrap;

use Doctrine\Common\Cache\RedisCache;
use EuroMillions\components\EnvironmentDetector;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\Common\Cache\ApcCache;
use EuroMillions\services\DomainServiceFactory;
use Phalcon\Cache\Frontend\Data;
use Phalcon\Config;
use Phalcon\Config\Adapter\Ini;
use Phalcon\Di;
use Redis;
use Phalcon\Cache\Backend\Redis as PhalconRedis;

abstract class BootstrapStrategyBase
{
    protected $configPath;
    private $globalConfigPath;

    public function __construct($globalConfigPath, $configPath)
    {
        $this->configPath = $configPath;
        $this->globalConfigPath = $globalConfigPath;
    }

    public function dependencyInjector()
    {
        $di = new Di();
        $global_config = $this->configGlobalConfig();
        $environment_detector = $this->configEnvironmentDetector($global_config);
        if (!$environment_detector->isEnvSet()) {
            $environment_detector->setDefault();
        }
        $config = $this->configConfig($environment_detector);
        $di->set('domainServiceFactory', $this->configDomainServiceFactory($di), true);
        $di->set('globalConfig', $global_config, true);
        $di->set('environmentDetector', $environment_detector);
        $di->set('config', $config, true);
        $di->set('entityManager', $this->configDoctrine($config), true);
        $di->set('redisCache', $this->configRedis($config), true);
        return $di;
    }

    protected function configDomainServiceFactory(Di $di)
    {
        return new DomainServiceFactory($di);
    }

    protected function configRedis(Ini $appConfig)
    {
        $frontend = new Data(['lifetime'=> 900]);
        return new PhalconRedis($frontend, ['host'=>$appConfig['redis']['host'], 'prefix'=>$appConfig['redis']['prefix']]);
    }

    protected function configDoctrine(Ini $appConfig)
    {
        $is_dev_mode = true; //EMDEPLOY hay que pasarlo por configuración. Quizá con el nuevo detector de environment
        $config = Setup::createYAMLMetadataConfiguration(array($this->configPath . 'doctrine'), $is_dev_mode);
        $config->setQueryCacheImpl(new ApcCache());
        $config->setMetadataCacheImpl(new ApcCache());

        $redis = new Redis();
        $redis->connect($appConfig['redis']['host']);
        $redis_cache = new RedisCache();
        $redis_cache->setRedis($redis);
        $redis_cache->setNamespace('doctrine_'.$appConfig['redis']['prefix']);
        $config->setResultCacheImpl($redis_cache);
        $conn = [
            'host'     => $appConfig['database']['host'],
            'driver'   => 'pdo_mysql',
            'user'     => $appConfig['database']['username'],
            'password' => $appConfig['database']['password'],
            'dbname'   => $appConfig['database']['dbname'],
        ];
        $em = EntityManager::create($conn, $config);
        $platform = $em->getConnection()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');
        return $em;
    }

    protected function configEnvironmentDetector(Ini $globalConfig)
    {
        $var_name = $globalConfig['environment']['var_name'];
        return new EnvironmentDetector($var_name);
    }

    protected function configConfig(EnvironmentDetector $em) {
        return new Ini($this->configPath.$this->getConfigFileName($em));
    }

    protected function configGlobalConfig()
    {
        return new Ini($this->globalConfigPath . 'config.ini');
    }

    abstract protected function getConfigFileName(EnvironmentDetector $em);
}