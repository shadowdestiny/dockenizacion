<?php


namespace EuroMillions\shareconfig\bootstrap;


use EuroMillions\admin\services\DomainAdminServiceFactory;
use EuroMillions\web\services\DomainServiceFactory;
use EuroMillions\web\services\ServiceFactory;
use Phalcon\Di;

trait BootstrapBehaviourShared
{

    protected function shareTheseServices(Di $di) {
        $di->set('domainServiceFactory', $this->setDomainServiceFactory($di), true);
        $di->set('domainAdminServiceFactory', $this->setDomainAdminServiceFactory($di),true);
    }

    private function setDomainServiceFactory($di)
    {
        return new DomainServiceFactory($di, new ServiceFactory($di));
    }

    private function setDomainAdminServiceFactory($di)
    {
        return new DomainAdminServiceFactory($di);
    }
}