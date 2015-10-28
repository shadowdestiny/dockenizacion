<?php


namespace tests\base;

use EuroMillions\web\services\DomainServiceFactory;
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
}