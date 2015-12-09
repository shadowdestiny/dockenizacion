<?php
namespace EuroMillions\shared\config\bootstrap;

use EuroMillions\shared\components\EnvironmentDetector;
use EuroMillions\shared\config\interfaces\IBootstrapStrategy;
use Phalcon\Cli\Console as ConsoleApp;
use Phalcon\Cli\Dispatcher;
use Phalcon\Cli\Router;
use Phalcon\Di;


class CliBootstrapStrategy extends BootstrapStrategyBase implements IBootstrapStrategy
{
    use BootstrapBehaviourShared;

    protected $commandLineArguments;
    protected $config;

    const CONFIG_FILENAME = 'cli_config.ini';

    public function __construct($commandLineArguments, $globalConfigPath, $configPath)
    {
        $this->commandLineArguments = $commandLineArguments;
        parent::__construct($globalConfigPath, $configPath);
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
        $this->shareTheseServices($di);
        return $di;
    }

    protected function configRouter()
    {
        return new Router();
    }

    protected function configDispatcher()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->setDefaultNamespace('EuroMillions\web\tasks');
        return $dispatcher;
    }

    protected function getConfigFileName(EnvironmentDetector $em)
    {
        return $em->get().'_'.self::CONFIG_FILENAME;
    }
}