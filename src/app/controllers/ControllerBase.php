<?php
namespace EuroMillions\controllers;

use EuroMillions\entities\User;
use EuroMillions\services\AuthService;
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

    /**
     * @param AuthService $authService
     * @return User
     */
    protected function forceLogin(AuthService $authService)
    {
        if(!$authService->isLogged())
        {
            $this->dispatcher->forward([
                'controller' => 'userAccess',
                'action'    => 'signIn',
                'params'    => [$this->dispatcher->getParams()],
            ]);
            return false;
        }
        return $authService->getCurrentUser();
    }
}