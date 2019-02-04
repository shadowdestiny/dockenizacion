<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 12/12/18
 * Time: 11:46
 */

namespace EuroMillions\shared\bootstrap;


use EuroMillions\shared\config\bootstrap\CliBootstrapStrategy;
use Phalcon\Cli\Dispatcher;

class SharedBootstrap extends CliBootstrapStrategy
{
    protected function configDispatcher()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->setDefaultNamespace('EuroMillions\shared\tasks');
        return $dispatcher;
    }

}