<?php
namespace app\config\bootstrap;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Phalcon\Config\Adapter\Ini;
use Phalcon\Di;

abstract class BootstrapStrategyBase
{

    protected $config;
    protected $configPath;

    public function __construct($configPath, $configFile)
    {
        $this->config = new Ini($configPath . $configFile);
        $this->configPath = $configPath;
    }

    public function setErrorReportingLevel()
    {
        error_reporting($this->config->application->errorReporting);
    }

    public function dependencyInjector()
    {
        $di = new Di();
        $di->set('config', $this->config, true);
        $di->set('entityManager', $this->configDoctrine(), true);
        return $di;
    }

    protected function configDoctrine()
    {
        $is_dev_mode = true; //EMDEPLOY hay que pasarlo por configuración
        $config = Setup::createYAMLMetadataConfiguration(array($this->configPath.'doctrine'), $is_dev_mode);
        $conn = [
            'host'     => $this->config->database->host,
            'driver'   => 'pdo_mysql',
            'user'     => $this->config->database->username,
            'password' => $this->config->database->password,
            'dbname'   => $this->config->database->dbname,
        ];
        $em = EntityManager::create($conn, $config);
        $platform = $em->getConnection()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');
        return $em;
    }
}