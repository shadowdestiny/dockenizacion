<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 8/01/19
 * Time: 15:47
 */

namespace EuroMillions\shared\bootstrap;

use EuroMillions\shared\components\PhalconUrlWrapper;
use EuroMillions\shared\config\bootstrap\BootstrapStrategyBase;
use EuroMillions\shared\interfaces\IBootstrapStrategy;
use Phalcon\Cli\Console as ConsoleApp;
use Phalcon\Cli\Dispatcher;
use Phalcon\Cli\Router;
use Phalcon\Di;


class CliSharedBootstrap extends BootstrapStrategyBase implements IBootstrapStrategy
{
    protected $commandLineArguments;
    protected $config;

    public function __construct($commandLineArguments, $configPath)
    {
        $this->commandLineArguments = $commandLineArguments;
        parent::__construct($configPath);
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
        $di->set('url', $this->configUrl(), true);
        return $di;
    }

    protected function configRouter()
    {
        return new Router();
    }

    protected function configDispatcher()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->setDefaultNamespace('EuroMillions\shared\tasks');
        return $dispatcher;
    }

    protected function configUrl()
    {
        $url = new PhalconUrlWrapper();
        $url->setBaseUri('https://localhost:4433');
        return $url;
    }
}