<?php

namespace EuroMillions\powerball\bootstrap;

use EuroMillions\shared\config\bootstrap\CliBootstrapStrategy;
use Phalcon\Cli\Dispatcher;


class PowerBallBootstrap extends CliBootstrapStrategy
{

    protected function configDispatcher()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->setDefaultNamespace('EuroMillions\powerball\tasks');
        return $dispatcher;
    }


}