<?php

namespace EuroMillions\eurojackpot\bootstrap;

use EuroMillions\shared\config\bootstrap\CliBootstrapStrategy;
use Phalcon\Cli\Dispatcher;


class EuroJackpotBootstrap extends CliBootstrapStrategy
{

    protected function configDispatcher()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->setDefaultNamespace('EuroMillions\eurojackpot\tasks');
        return $dispatcher;
    }


}