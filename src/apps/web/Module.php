<?php


namespace EuroMillions\web;


use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\View;
use Phalcon\Events\Event;
use Phalcon\Events\Manager;


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
            $eventsManager = new Manager();
            $eventsManager->attach("dispatch", function (Event $event, Dispatcher $dispatcher, \Exception $exception = null) {
                //The controller exists but the action not
                if ($event->getType() === 'beforeNotFoundAction') {
                    $dispatcher->forward(array(
                        'module'     => 'web',
                        'controller' => 'index',
                        'action'     => 'notfound'
                    ));
                    return false;
                }
                if ($event->getType() === 'beforeException') {
                    switch ($exception->getCode()) {
                        case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                        case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                            $dispatcher->forward(array(
                                'module'     => 'web',
                                'controller' => 'index',
                                'action'     => 'notfound',
                                'params'     => array($exception)
                            ));
                            return false;
                    }
                }
                return true;
            });
            $dispatcher = new Dispatcher();
            $dispatcher->setEventsManager($eventsManager);
            $dispatcher->setDefaultNamespace('EuroMillions\web\controllers');
            return $dispatcher;
        });

        //  $dependencyInjector->set('view', $this->configView(), true);

    }

    protected function configView()
    {

    }
}