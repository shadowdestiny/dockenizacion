<?php

namespace EuroMillions\admin\controllers;

use EuroMillions\admin\services\UserAdminService;

class AccountController extends AdminControllerBase
{

    /** @var UserAdminService $userAdminService */
    private $userAdminService;

    public function initialize()
    {
        parent::initialize();
        $this->userAdminService = $this->domainAdminServiceFactory->getUserAdminService();
    }

    /**
     * @return \Phalcon\Mvc\View
     */
    public function indexAction()
    {
        if ($this->request->getPost('changePassword') == '1') {
            return $this->view->setVars([
                'userAdmin' => $this->userAdminService->getUserAdminById($this->session->get('userAdminId')),
                'errorMessage' => $this->userAdminService->savePassword($this->request->getPost()),
            ]);
        } else {
            if ($this->session->get('userAdminId')) {
                return $this->view->setVars([
                    'userAdmin' => $this->userAdminService->getUserAdminById($this->session->get('userAdminId')),
                    'errorMessage' => '',
                ]);
            }
            return $this->view->setVars([
                'userAdmin' => '',
                'errorMessage' => '',
            ]);
        }
    }
}