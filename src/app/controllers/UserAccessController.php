<?php
namespace EuroMillions\controllers;

use EuroMillions\forms\SignInForm;
use Phalcon\Validation\Message;

class UserAccessController extends ControllerBase
{
    public function signInAction($paramsFromPreviousAction = null)
    {
        $errors = null;
        $auth_service = $this->domainServiceFactory->getAuthService();
        $form = new SignInForm();
        if (!$this->request->isPost()) {
            if($auth_service->hasRememberMe()) {
                return $auth_service->loginWithRememberMe();
            }
        } else {
            if ($form->isValid($this->request->getPost()) == false) {
                $messages = $form->getMessages(true);
                /**
                 * @var string $field
                 * @var Message\Group $field_messages
                 */
                foreach($messages as $field => $field_messages) {
                    $errors[] = $field_messages[0]->getMessage();
                }
            } else {
                if (!$auth_service->check([
                    'email' => $this->request->getPost('email'),
                    'password' => $this->request->getPost('password'),
                    'remember' => $this->request->getPost('remember'),
                ], 'string')) {
                    $errors[] = 'Email/password combination not valid';
                } else {
                    $dispatcher = $this->getDI()->get('dispatcher');
                    $controller = $dispatcher->getPreviousControllerName();
                    $action = $dispatcher->getPreviousActionName();
                    $redirect = "$controller/$action/$paramsFromPreviousAction";
                    $this->response->redirect($redirect);
                }
            }
        }
        $this->view->pick('sign-in/index');
        return $this->view->setVars(['form' => $form, 'errors' => $errors]);
    }
}