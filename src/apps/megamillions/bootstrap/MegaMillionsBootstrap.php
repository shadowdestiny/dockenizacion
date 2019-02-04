<?php

namespace EuroMillions\megamillions\bootstrap;

use EuroMillions\shared\config\bootstrap\CliBootstrapStrategy;
use Phalcon\Cli\Dispatcher;


class MegaMillionsBootstrap extends CliBootstrapStrategy
{

    protected function configDispatcher()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->setDefaultNamespace('EuroMillions\megamillions\tasks');
        return $dispatcher;
    }


}