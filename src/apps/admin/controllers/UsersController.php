<?php


namespace EuroMillions\admin\controllers;

use EuroMillions\admin\services\MaintenanceUserService;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\admin\vo\dto\UserDTO;
use EuroMillions\web\entities\User;

class UsersController extends AdminControllerBase
{

    /** @var  MaintenanceUserService */
    private $maintenanceUserService;


    public function initialize()
    {
        parent::initialize();
        $this->maintenanceUserService = $this->domainAdminServiceFactory->getMaintenanceUserService();
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

    public function viewAction()
    {
        $id = $this->request->get('id');
        /** @var ActionResult $result */
        $result = $this->maintenanceUserService->getUser($id);
        $this->noRender();
        if($result->success()){
            /** @var UserDTO $user */
            $user = new UserDTO($result->getValues());
            echo json_encode(['result'=> 'OK',
                              'value' => $user->toArray()
            ]);
        }else{
            echo json_encode(['result'=> 'KO',
                              'value' => $result->errorMessage()
            ]);
        }
    }

    public function editAction()
    {
        $email = $this->request->get('email');
        if(!empty($email)){

            $result = $this->maintenanceUserService->updateUserData($this->request);
            $this->noRender();
            if($result->success()) {
                $result_list = $this->maintenanceUserService->listAllUsers();
                $list_users_dto = [];
                if($result_list->success()){
                    $values = $result_list->getValues();
                    foreach($values as $user) {
                        $list_users_dto[] = new UserDTO($user);
                    }
                    echo json_encode(['result' => 'OK',
                                      'value' => $list_users_dto
                                     ]
                    );
                } else{
                    echo json_encode(['result' => 'KO',
                                      'value'=> $result_list->errorMessage()
                                      ]
                    );
                }

            } else{
                echo json_encode(['result' => 'KO',
                                  'value'=> $result->errorMessage()
                                 ]
                );
            }
        }
   }
}