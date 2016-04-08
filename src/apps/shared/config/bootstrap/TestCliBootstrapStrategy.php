<?php
namespace EuroMillions\shared\config\bootstrap;

use EuroMillions\shared\components\EnvironmentDetector;
use EuroMillions\shared\components\PhalconUrlWrapper;
use EuroMillions\shared\interfaces\IBootstrapStrategy;
use Phalcon\Cli\Console as ConsoleApp;
use Phalcon\Cli\Dispatcher;
use Phalcon\Cli\Router;
use Phalcon\Di;


class TestCliBootstrapStrategy extends CliBootstrapStrategy implements IBootstrapStrategy
{
//    const CONFIG_FILENAME = 'test_cli_config.ini';
//
//    protected function getConfigFileName(EnvironmentDetector $em)
//    {
//        return $em->get().'_'.self::CONFIG_FILENAME;
//    }
//
//    protected function configUrl()
//    {
//        $url = new PhalconUrlWrapper();
//        $url->setBaseUri('https://localhost:4433');
//        return $url;
//    }
}