<?php

namespace EuroMillions\admin\controllers;

use EuroMillions\admin\forms\LoginForm;
use Phalcon\Validation\Message;

class LoginController extends AdminControllerBase
{
    public function indexAction($paramsFromPreviousAction = null)
    {

        $auth_user_service = $this->domainAdminServiceFactory->getAuthUserService();
        $errors = [];
        $sign_in_form = new LoginForm();
        list($controller, $action, $params) = $this->getPreviousParams($paramsFromPreviousAction);
        if ($this->request->isPost()) {
            if ($sign_in_form->isValid($this->request->getPost()) == false) {
                $messages = $sign_in_form->getMessages(true);
                /**
                 * @var string $field
                 * @var Message\Group $field_messages
                 */
                foreach ($messages as $field => $field_messages) {
                    $errors[] = $field_messages[0]->getMessage();
                    $form_errors[$field] = ' error';
                }
            } else {
                $result = $auth_user_service->login([
                    'email'    => $this->request->getPost('username'),
                    'pass' => md5($this->request->getPost('password'))
                ]);

                if (!$result->success()) {
                    $errors[] = 'Username/password combination not valid';
                } else {
                    if (!empty($controller) && !empty($action) && !empty($params)) {
                        return $this->response->redirect('/admin/'.$controller.'/'.$action.'/'.$params);
                    } else {
                        return $this->response->redirect('/admin/index/index');
                    }
                }
            }
        }
        
        return $this->view->setVars([
            'signinform'  => $sign_in_form,
            'errors'      => $errors,
            'controller' => $controller,
            'action' => $action,
            'params' => json_encode($params),
        ]);
    }

    /**
     * @param $paramsFromPreviousAction
     * @return array
     */
    private function getPreviousParams($paramsFromPreviousAction)
    {
        if ($this->request->isPost()) {
            $controller = $this->request->getPost('controller');
            $action = $this->request->getPost('action');
            $params = json_decode($this->request->getPost('params'));
            return array($controller, $action, $params);
        } else {
            $controller = $this->dispatcher->getPreviousControllerName();
            $action = $this->dispatcher->getPreviousActionName();
            $params = $paramsFromPreviousAction;
            return array($controller, $action, $params);
        }
    }
}