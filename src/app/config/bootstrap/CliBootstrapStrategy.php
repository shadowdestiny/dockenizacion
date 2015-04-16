<?php
namespace app\config\bootstrap;

use app\interfaces\IBootstrapStrategy;
use Phalcon\Cli\Console as ConsoleApp;
use Phalcon\Di;


class CliBootstrapStrategy extends BootstrapStrategyBase implements IBootstrapStrategy
{
    protected $commandLineArguments;
    protected $config;

    public function __construct($commandLineArguments, $configFile)
    {
        $this->$commandLineArguments = $commandLineArguments;
        parent::__construct(, $configFile);
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
        return $di;
    }
}