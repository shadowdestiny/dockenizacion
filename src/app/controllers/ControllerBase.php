<?php
namespace EuroMillions\controllers;

use EuroMillions\services\DomainServiceFactory;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;

class ControllerBase extends Controller
{
    /** @var  DomainServiceFactory */
    protected $domainServiceFactory;

    public function initialize()
    {
        $this->domainServiceFactory = $this->di->get('domainServiceFactory');
    }

    protected function noRender()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
    }
}