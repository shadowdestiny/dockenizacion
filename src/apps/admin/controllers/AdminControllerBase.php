<?php


namespace EuroMillions\admin\controllers;


use EuroMillions\admin\services\DomainAdminServiceFactory;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;

class AdminControllerBase extends Controller
{

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
    //EMTD discomment to protect session
/*    public function afterExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {
        $auth_user_service = $this->domainAdminServiceFactory->getAuthUserService();
        if($dispatcher->getControllerName() != 'login') {
            if(!$auth_user_service->check_session()->success()){
                return $this->response->redirect('admin/login/index');
            }
        }
    }*/

}