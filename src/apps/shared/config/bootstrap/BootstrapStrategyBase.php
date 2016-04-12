<?php
namespace EuroMillions\shared\config\bootstrap;

use Doctrine\Common\Cache\ApcuCache;
use Doctrine\Common\Cache\RedisCache;
use Doctrine\DBAL\Types\Type;
use EuroMillions\admin\services\DomainAdminServiceFactory;
use EuroMillions\shared\components\EnvironmentDetector;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use EuroMillions\web\services\card_payment_providers\factory\PaymentProviderFactory;
use EuroMillions\web\services\card_payment_providers\PayXpertCardPaymentStrategy;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\services\factories\ServiceFactory;
use Phalcon\Config;
use Phalcon\Config\Adapter\Ini;
use Phalcon\Crypt;
use Phalcon\Di;
use Phalcon\Mvc\Collection;
use Redis;

abstract class BootstrapStrategyBase
{
    protected $configPath;
    const CONFIG_FILENAME = 'config.ini';

    public function __construct($configPath)
    {
        $this->configPath = $configPath;
    }

    public function dependencyInjector()
    {
        $di = new Di();
        $environment_detector = $this->configEnvironmentDetector();
        if (!$environment_detector->isEnvSet()) {
            $environment_detector->setDefault();
        }
        $config = $this->configConfig($environment_detector);
        $di->set('crypt', $this->configCrypt(), true);
        $di->set('configPath', function() {return $this->configPath;}, true);
        $di->set('environmentDetector', $environment_detector);
        $di->set('config', $config, true);
        $redis = $this->configRedis($config);
        $di->set('redisCache', $redis, true);
        $di->set('entityManager', $this->configDoctrine($config, $redis), true);
        $di->set('paymentProviderFactory', $this->configPaymentProvider($di), true);
        $di->set('domainServiceFactory', $this->setDomainServiceFactory($di), true);
        $di->set('domainAdminServiceFactory', $this->setDomainAdminServiceFactory($di),true);
       
        return $di;
    }
    private function setDomainServiceFactory($di)
    {
        return new DomainServiceFactory($di, new ServiceFactory($di));
    }

    private function setDomainAdminServiceFactory($di)
    {
        return new DomainAdminServiceFactory($di);
    }


    protected function configCrypt()
    {
        $crypt = new Crypt();
        $crypt->setKey('8p2904fj@¢#al/4'); // Use your own key!
        return $crypt;
    }

    protected function configDomainServiceFactory(Di $di)
    {
        return new DomainServiceFactory($di, new ServiceFactory($di));
    }

    protected function configRedis(Ini $appConfig)
    {
        $redis = new Redis();
        $redis->connect($appConfig['redis']['host']);
        $redis->setOption(Redis::OPT_PREFIX, $appConfig['redis']['prefix']);
        return $redis;
    }

    protected function configDoctrine(Ini $appConfig, Redis $redis)
    {
        $is_dev_mode = true; //EMDEPLOY hay que pasarlo por configuración. Quizá con el nuevo detector de environment


        $config = Setup::createXMLMetadataConfiguration(array($this->configPath . 'doctrine'), $is_dev_mode);
        $config->setQueryCacheImpl(new ApcuCache());
        $config->setMetadataCacheImpl(new ApcuCache());

        $redis_cache = new RedisCache();
        $redis_cache->setRedis($redis);
        $redis_cache->setNamespace($appConfig['redis']['prefix']);
        $config->setResultCacheImpl($redis_cache);
        $conn = [
            'host'     => $appConfig['database']['host'],
            'driver'   => 'pdo_mysql',
            'user'     => $appConfig['database']['username'],
            'dbname'   => $appConfig['database']['dbname'],
            'charset'  => 'utf8'
        ];
        if ('' !== $appConfig['database']['password']) {
            $conn['password'] = $appConfig['database']['password'];
        }

        $em = EntityManager::create($conn, $config);
        if (!Type::hasType('uuid')) {
            Type::addType('uuid', 'Ramsey\Uuid\Doctrine\UuidType');
        }
        $platform = $em->getConnection()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');
        $platform->registerDoctrineTypeMapping('uuid', 'uuid');
        return $em;
    }

    protected function configEnvironmentDetector()
    {
        return new EnvironmentDetector();
    }

    protected function configConfig(EnvironmentDetector $em) {
        return new Ini($this->configPath.$this->getConfigFileName($em));
    }

    protected function configPaymentProvider(Di $di)
    {
        $paymentProviderFactory = new PaymentProviderFactory();
        $config_payment = $di->get('config')['payxpert'];
        return $paymentProviderFactory->getCreditCardPaymentProvider(new PayXpertCardPaymentStrategy($config_payment));
    }

    protected function getConfigFileName(EnvironmentDetector $em)
    {
        return $em->get().'_'.self::CONFIG_FILENAME;
    }

}