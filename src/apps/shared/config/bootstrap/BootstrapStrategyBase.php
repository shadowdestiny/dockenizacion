<?php
namespace EuroMillions\shared\config\bootstrap;

use Doctrine\Common\Cache\ApcuCache;
use Doctrine\Common\Cache\RedisCache;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Events;
use EuroMillions\admin\services\DomainAdminServiceFactory;
use EuroMillions\shared\components\DBUtil;
use EuroMillions\shared\components\EnvironmentDetector;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use EuroMillions\shared\events\PlayConfigTransactionEventSubscriber;
use EuroMillions\web\services\card_payment_providers\factory\PaymentProviderFactory;
use EuroMillions\web\services\card_payment_providers\payments_util\PaymentsCollection;
use EuroMillions\web\services\card_payment_providers\payments_util\PaymentsRegistry;
use EuroMillions\web\services\card_payment_providers\PayXpertCardPaymentStrategy;
use EuroMillions\web\services\card_payment_providers\pgwlb\RandomStrategy;
use EuroMillions\web\services\card_payment_providers\WideCardPaymentStrategy;
use EuroMillions\shared\services\payments_load_balancer_strategies\GeoIPStrategy;
use EuroMillions\web\services\criteria_strategies\CountryCriteria;
use EuroMillions\web\services\criteria_strategies\CriteriaSelector;
use EuroMillions\web\services\factories\CollectionPaymentCriteriaFactory;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\services\factories\ServiceFactory;
use EuroMillions\web\vo\enum\PaymentSelectorType;
use EuroMillions\web\vo\PaymentCountry;
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
        $di->set('configPath', function () {
            return $this->configPath;
        }, true);
        $di->set('environmentDetector', $environment_detector);
        $di->set('config', $config, true);
        $redis = $this->configRedis($config);
        $di->set('redisCache', $redis, true);
        $di->set('entityManager', $this->configDoctrine($config, $redis), true);
        $di->set('paymentsCollection', $this->registerPayments($di,$this->paymentsCollection()), true);
        $di->set('paymentStrategy', $this->loadPaymentGateWayByStrategy($di), true);
        $di->set('paymentProviderFactory', $this->configPaymentProvider($di), true);
        $di->set('domainServiceFactory', $this->setDomainServiceFactory($di), true);
        $di->set('domainAdminServiceFactory', $this->setDomainAdminServiceFactory($di), true);

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
        $is_dev_mode = $appConfig['database']['is_dev_mode'];

        $config = Setup::createXMLMetadataConfiguration(array($this->configPath . 'doctrine'), $is_dev_mode);
        $config->setQueryCacheImpl(new ApcuCache());
        $config->setMetadataCacheImpl(new ApcuCache());
        $redis_cache = new RedisCache();
        $redis_cache->setRedis($redis);
        $redis_cache->setNamespace('result_');
        $config->setResultCacheImpl($redis_cache);
        $conn = DBUtil::configConnection($appConfig);
        $eventManager = new EventManager();
        $eventManager->addEventSubscriber(new PlayConfigTransactionEventSubscriber());
        $em = EntityManager::create($conn, $config, $eventManager);
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


    protected function paymentsCollection()
    {
        return new \EuroMillions\shared\components\PaymentsCollection();
    }

    protected function configConfig(EnvironmentDetector $em)
    {
        return new Ini($this->configPath . $this->getConfigFileName($em));
    }

    protected function registerPayments(Di $di, \EuroMillions\shared\components\PaymentsCollection $paymentsCollection)
    {
        $paymentGatewayLoader = $di->get('config')['payment_gateway'];
        $configPaymentGatewayLoader = explode(',',$paymentGatewayLoader->config);
        try {
            foreach (explode(',', $paymentGatewayLoader->class_strategy) as $k => $class_strategy) {
                $class = "\\EuroMillions\\web\\services\\card_payment_providers\\" . $class_strategy;
                $paymentsCollection->addItem($class_strategy,new $class($di->get('config')[$configPaymentGatewayLoader[$k]]));
            }
            return $paymentsCollection;
        } catch (\Exception $e)
        {
            throw new \Exception('An error occurred while payments were registered');
        }
    }

    protected function loadPaymentGateWayByStrategy(Di $di)
    {
        $paymentsCollection = $di->get('paymentsCollection');
        $paymentStrategy = $di->get('config')['payment_balancing'];
        $dependencies = $di->get('config')[$paymentStrategy->dependencies];
        $paymentInstanceStrategy = "\\EuroMillions\\shared\\services\\payments_load_balancer_strategies\\".$paymentStrategy->strategy;
        $payment = new $paymentInstanceStrategy($paymentsCollection,$dependencies);
        return $payment;
    }


    protected function configPaymentProvider(Di $di)
    {
        $paymentProviderFactory = new PaymentProviderFactory();
        //Gets payment gateway from config.ini
        $paymentGatewayLoader = $di->get('config')['payment_gateway'];
        foreach (explode(',', $paymentGatewayLoader->class_strategy) as $k => $class_strategy) {
            $class = "\\EuroMillions\\web\\services\\card_payment_providers\\" . $class_strategy;
            new $class;
        }
        $configPayments = explode(',', $paymentGatewayLoader->config);
        $paymentStrategy = $di->get('config')['payment_balancing'];

//        $paymentStrategy = "\\EuroMillions\\web\\services\\card_payment_providers\\pgwlb\\" . $paymentStrategy->strategy . 'Strategy';
//        $paymentInstance = new $paymentStrategy(new PaymentsRegistry($configPayments));
        $paymentGateway = $di->get('paymentStrategy')->getInstance();
        return $paymentProviderFactory->getCreditCardPaymentProvider($di->get('paymentStrategy')->getInstance());
    }

    protected function getConfigFileName(EnvironmentDetector $em)
    {
        return $em->get() . '_' . self::CONFIG_FILENAME;
    }

}