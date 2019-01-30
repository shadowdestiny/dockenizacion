<?php


namespace EuroMillions\admin\controllers;


use EuroMillions\admin\services\AuthUserService;
use EuroMillions\admin\services\DomainAdminServiceFactory;
use EuroMillions\admin\services\ReportsService;
use EuroMillions\shared\helpers\PaginatedControllerTrait;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;

class AdminControllerBase extends Controller
{

    const ALLOW_IPS = ['62.57.159.66', '88.15.234.137','80.28.218.1', '83.63.153.86'];

    use PaginatedControllerTrait;
    /** @var  DomainAdminServiceFactory */
    protected $domainAdminServiceFactory;
    /** @var  ReportsService */
    protected $reportsService;

    public function initialize()
    {
        $this->domainAdminServiceFactory = $this->di->get('domainAdminServiceFactory');
        $this->reportsService = $this->domainAdminServiceFactory->getReportsService();
    }

    protected function noRender()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
    }

    public function beforeExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher) {
       if($this->di->get('environmentDetector')->get() === 'production' ) {
         if(!in_array($this->request->getClientAddress(true), self::ALLOW_IPS)){
             die('You have not access');
         }
       }
    }


    public function afterExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {
        $auth_user_service = $this->domainAdminServiceFactory->getAuthUserService();
        if($dispatcher->getControllerName() !== 'login' && !$auth_user_service->check_session()->success()) {
            return $this->response->redirect('/admin/login/index');
        } else {
            $this->session->set(AuthUserService::CURRENT_ADMIN_USER_VAR, time());
            $this->session->set('userAdminId', $this->session->get('userAdminId'));
            $this->session->set('userAdminAccess', $this->session->get('userAdminAccess'));
        }
    }
}