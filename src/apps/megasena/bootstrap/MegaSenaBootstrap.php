<?php

namespace EuroMillions\megasena\bootstrap;

use EuroMillions\shared\config\bootstrap\CliBootstrapStrategy;
use Phalcon\Cli\Dispatcher;


class MegaSenaBootstrap extends CliBootstrapStrategy
{

    protected function configDispatcher()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->setDefaultNamespace('EuroMillions\megasena\tasks');
        return $dispatcher;
    }


}