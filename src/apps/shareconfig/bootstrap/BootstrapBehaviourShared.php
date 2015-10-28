<?php


namespace EuroMillions\shareconfig\bootstrap;


use EuroMillions\web\services\DomainServiceFactory;
use EuroMillions\web\services\ServiceFactory;
use Phalcon\Di;

trait BootstrapBehaviourShared
{

    protected function shareTheseServices(Di $di) {
        $di->set('domainServiceFactory', $this->setDomainServiceFactory($di), true);
    }

    private function setDomainServiceFactory($di)
    {
        return new DomainServiceFactory($di, new ServiceFactory($di));
    }
}