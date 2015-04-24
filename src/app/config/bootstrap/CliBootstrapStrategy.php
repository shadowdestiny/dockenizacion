<?php
namespace app\config\bootstrap;

use app\components\EnvironmentDetector;
use app\interfaces\IBootstrapStrategy;
use Phalcon\Cli\Console as ConsoleApp;
use Phalcon\Cli\Dispatcher;
use Phalcon\Cli\Router;
use Phalcon\Di;


class CliBootstrapStrategy extends BootstrapStrategyBase implements IBootstrapStrategy
{
    protected $commandLineArguments;
    protected $config;

    const CONFIG_FILENAME = 'cliconfig.ini';

    public function __construct($commandLineArguments, $globalConfigPath, $configPath, $configFile)
    {
        $this->commandLineArguments = $commandLineArguments;
        parent::__construct($globalConfigPath, $configPath, $configFile);
    }

    public function execute(Di $di)
    {
        $arguments = array();
        foreach ($this->commandLineArguments as $k => $arg) {
            if ($k == 1) {
                $arguments['task'] = $arg;
            } elseif ($k == 2) {
                $arguments['action'] = $arg;
            } elseif ($k >= 3) {
                $arguments['params'][] = $arg;
            }
        }
        $console = new ConsoleApp();
        $console->setDI($di);
        $console->handle($arguments);
    }

    /**
     * @return \Phalcon\Di
     */
    public function dependencyInjector()
    {
        $di = parent::dependencyInjector();
        $di->set('router', $this->configRouter(), true);
        $di->set('dispatcher', $this->configDispatcher(), true);
        return $di;
    }

    protected function configRouter()
    {
        return new Router();
    }

    protected function configDispatcher()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->setDefaultNamespace('app\tasks');
        return $dispatcher;
    }

    protected function getConfigFileName(EnvironmentDetector $em)
    {
        return self::CONFIG_FILENAME;
    }
}