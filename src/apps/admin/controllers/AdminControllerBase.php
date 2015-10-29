<?php


namespace EuroMillions\admin\controllers;


use EuroMillions\admin\services\DomainAdminServiceFactory;
use Phalcon\Mvc\Controller;

class AdminControllerBase extends Controller
{

    /** @var  DomainAdminServiceFactory */
    protected $domainAdminServiceFactory;

    public function initialize()
    {
        $this->domainAdminServiceFactory = $this->di->get('domainAdminServiceFactory');
    }

}