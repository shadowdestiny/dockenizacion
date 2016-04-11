<?php


namespace EuroMillions\web;


use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\View;

class Module implements ModuleDefinitionInterface
{
    /**
     * Registers services related to the module
     *
     * @param mixed $dependencyInjector
     */
    public function registerServices(\Phalcon\DiInterface $dependencyInjector)
    {

            

          //  $dependencyInjector->set('view', $this->configView(), true);

    }

    protected function configView()
    {

    }
}