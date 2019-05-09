<?php

namespace EuroMillions\superenalotto\bootstrap;

use EuroMillions\shared\config\bootstrap\CliBootstrapStrategy;
use Phalcon\Cli\Dispatcher;


class SuperEnalottoBootstrap extends CliBootstrapStrategy
{

    protected function configDispatcher()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->setDefaultNamespace('EuroMillions\superenalotto\tasks');
        return $dispatcher;
    }


}