<?php


namespace EuroMillions\web;


use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\View;

class Module implements ModuleDefinitionInterface
{

    /**
     * Registers an autoloader related to the module
     *
     * @param mixed $dependencyInjector
     */
    public function registerAutoloaders(\Phalcon\DiInterface $dependencyInjector = null)
    {
        $loader = new Loader();
        $loader->registerNamespaces(
            array(
                'EuroMillions\admin\controllers' => '../apps/web/controllers/',
            )
        );
        $loader->register();
    }

    /**
     * Registers services related to the module
     *
     * @param mixed $dependencyInjector
     */
    public function registerServices(\Phalcon\DiInterface $dependencyInjector)
    {

            $dependencyInjector->set('dispatcher', function () {
                $dispatcher = new Dispatcher();
                $dispatcher->setDefaultNamespace('EuroMillions\web\controllers');
                return $dispatcher;
            });

          //  $dependencyInjector->set('view', $this->configView(), true);

    }

    protected function configView()
    {

    }
}