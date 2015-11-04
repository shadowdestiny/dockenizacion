<?php


namespace EuroMillions\admin\controllers;

use EuroMillions\admin\services\MaintenanceUserService;
use EuroMillions\admin\vo\dto\UserDTO;
use EuroMillions\web\entities\User;

class UsersController extends AdminControllerBase
{

    /** @var  MaintenanceUserService */
    private $maintenanceUserService;

    public function initialize()
    {
        parent::initialize();
        $this->maintenanceUserService = $this->domainAdminServiceFactory->getMaintenanceService();
    }

    public function indexAction()
    {
        $result = $this->maintenanceUserService->listAllUsers();
        $list_users_dto = [];
        if($result->success()) {
            /** @var User $list_user */
            $list_users = $result->getValues();
            foreach($list_users as $user){
                $list_users_dto[] = new UserDTO($user);
            }
        }
        $this->view->pick('users/users');
        return $this->view->setVars([
           'users' => $list_users_dto
        ]);
    }

    public function searchAction()
    {

    }

    public function editAction()
    {

    }

    public function deleteAction($id)
    {

    }

}