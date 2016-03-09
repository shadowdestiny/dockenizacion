<?php


namespace EuroMillions\tests\base;


use EuroMillions\admin\services\DomainAdminServiceFactory;
use EuroMillions\web\services\factories\DomainServiceFactory;
use Phalcon\Di;

trait PhalconDiRelatedTest {
    protected function getDi()
    {
        return Di::getDefault();
    }

    /**
     * @return DomainServiceFactory
     */
    protected function getDomainServiceFactory()
    {
        return Di::getDefault()->get('domainServiceFactory');
    }

    /**
     * @return DomainAdminServiceFactory
     */
    protected function getDomainAdminServiceFactory()
    {
        return Di::getDefault()->get('domainAdminServiceFactory');
    }

}