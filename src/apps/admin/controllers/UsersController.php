<?php


namespace EuroMillions\admin\controllers;

use EuroMillions\admin\services\MaintenanceUserService;
use EuroMillions\shared\components\widgets\PaginationWidgetAdmin;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\admin\vo\dto\UserDTO;
use EuroMillions\web\entities\User;

class UsersController extends AdminControllerBase
{

    /** @var  MaintenanceUserService */
    private $maintenanceUserService;


    public function initialize()
    {
        $this->checkPermissions();
        parent::initialize();
        $this->maintenanceUserService = $this->domainAdminServiceFactory->getMaintenanceUserService();
    }

    public function indexAction()
    {
        $result = $this->maintenanceUserService->listAllUsers();

        $users = $this->maintenanceUserService->getNumberOfUsers(($this->request->get('pageUsers') - 1) * 100);
        $pageUsers = (!empty($this->request->get('pageUsers'))) ? $this->request->get('pageUsers') : 1;
        $paginatorUsers = $this->getPaginatorAsArray(!empty($users) ? $users : [], 1, $pageUsers);
        $paginatorViewUsers = (new PaginationWidgetAdmin($paginatorUsers, $this->request->getQuery(), [], 'pageUsers'))->render();
        $list_users_dto = [];
        if ($users) {
            /** @var User $list_user */
            foreach ($users as $user) {
                $list_users_dto[] = new UserDTO($user);
            }
        }
        $this->view->pick('users/users');
        return $this->view->setVars([
            'users' => $list_users_dto,
            'userBets' => $paginatorUsers->getPaginate()->items,
            'paginator_view_bets' => $paginatorViewUsers,
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
        if ($result->success()) {
            /** @var UserDTO $user */
            $user = new UserDTO($result->getValues());
            echo json_encode(['result' => 'OK',
                'value' => $user->toArray()
            ]);
        } else {
            echo json_encode(['result' => 'KO',
                'value' => $result->errorMessage()
            ]);
        }
    }

    public function editAction()
    {
        $email = $this->request->get('email');
        if (!empty($email)) {

            $result = $this->maintenanceUserService->updateUserData($this->request);
            $this->noRender();
            if ($result->success()) {
                $result_list = $this->maintenanceUserService->listAllUsers();
                $list_users_dto = [];
                if ($result_list->success()) {
                    $values = $result_list->getValues();
                    foreach ($values as $user) {
                        $list_users_dto[] = new UserDTO($user);
                    }
                    echo json_encode(['result' => 'OK',
                            'value' => $list_users_dto
                        ]
                    );
                } else {
                    echo json_encode(['result' => 'KO',
                            'value' => $result_list->errorMessage()
                        ]
                    );
                }

            } else {
                echo json_encode(['result' => 'KO',
                        'value' => $result->errorMessage()
                    ]
                );
            }
        }
    }

    private function checkPermissions()
    {
        if (strpos('S', $this->session->get('userAdminAccess')) !== false) {
            return $this->response->redirect('/admin/index/notaccess');
        }
    }

}