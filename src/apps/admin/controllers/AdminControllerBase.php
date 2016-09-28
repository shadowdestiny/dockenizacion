<?php


namespace EuroMillions\admin\controllers;


use EuroMillions\admin\services\DomainAdminServiceFactory;
use EuroMillions\shared\helpers\PaginatedControllerTrait;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;

class AdminControllerBase extends Controller
{

    const ALLOW_IPS = ['62.57.159.66'];

    use PaginatedControllerTrait;
    /** @var  DomainAdminServiceFactory */
    protected $domainAdminServiceFactory;

    public function initialize()
    {
        $this->domainAdminServiceFactory = $this->di->get('domainAdminServiceFactory');
    }

    protected function noRender()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
    }

    public function beforeExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher) {
       if($this->di->get('environmentDetector')->get() === 'production' ) {
         if(!in_array($this->request->getClientAddress(), self::ALLOW_IPS)){
             die('You have not access');
         }
       }
    }


    public function afterExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {

        $auth_user_service = $this->domainAdminServiceFactory->getAuthUserService();
        if($dispatcher->getControllerName() !== 'login' && !$auth_user_service->check_session()->success()) {
            return $this->response->redirect('/admin/login/index');
        }
    }
}