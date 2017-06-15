<?php

namespace EuroMillions\admin\controllers;

use EuroMillions\admin\services\UserAdminService;

class UsersadminController extends AdminControllerBase
{

    /** @var UserAdminService $userAdminService */
    private $userAdminService;

    public function initialize()
    {
        $this->checkPermissions();
        parent::initialize();
        $this->userAdminService = $this->domainAdminServiceFactory->getUserAdminService();
    }

    /**
     * @return \Phalcon\Mvc\View
     */
    public function indexAction()
    {
        $message = $this->cookies->get('message');
        $this->cookies->set('message', '');
        return $this->view->setVars([
            'adminUsers' => $this->userAdminService->getAllAdminUsers(),
            'errorMessage' => $message,
        ]);
    }

    /**
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function createUserAdminAction()
    {
        if (!empty($this->request->getPost('email'))) {
            if (!$this->userAdminService->existUserAdmin($this->request->getPost('email'))) {
                $this->userAdminService->createUserAdmin([
                    'name' => $this->request->getPost('name'),
                    'surname' => $this->request->getPost('surname'),
                    'email' => $this->request->getPost('email'),
                    'password' => md5($this->request->getPost('password')),
                    'useraccess' => $this->request->getPost('useraccess'),
                ]);

                return $this->redirectToIndex('User created correctly');
            }
        }

        return $this->redirectToIndex('User wasn\'t created');
    }

    /**
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function editUserAdminAction()
    {
        if (!empty($this->request->getPost('id'))) {
            $this->userAdminService->editUserAdminFromAdmin($this->request->getPost());
            return $this->redirectToIndex('User edited correctly');
        }

        return $this->redirectToIndex('User wasn\'t edited');
    }

    public function deleteUserAction()
    {
        if (!empty($this->request->get('id'))) {
            $this->userAdminService->deleteUserById($this->request->get('id'));
            return $this->redirectToIndex('User deleted correctly');
        }

        return $this->redirectToIndex('User wasn\'t deleted');
    }

    /**
     * @param null $message
     *
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    private function redirectToIndex($message = null)
    {
        $this->cookies->set('message', $message);
        return $this->response->redirect('/admin/usersadmin/index');
    }

    private function checkPermissions()
    {
        if (strpos('S', $this->session->get('userAdminAccess'))  !== false) {
            echo 'no entra';
            exit;
        }
    }
}