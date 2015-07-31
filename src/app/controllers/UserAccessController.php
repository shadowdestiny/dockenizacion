<?php
namespace EuroMillions\controllers;

use EuroMillions\forms\SignInForm;
use EuroMillions\forms\SignUpForm;
use Phalcon\Validation\Message;

class UserAccessController extends ControllerBase
{
    public function signInAction($paramsFromPreviousAction = null)
    {
        $errors = null;
        $auth_service = $this->domainServiceFactory->getAuthService();
        $sign_in_form = new SignInForm();
        $in_form_errors = [
            'email' => '',
            'password' => '',
        ];
        $sign_up_form = new SignUpForm();
        $up_form_errors = [
            'name' => '',
            'surname' => '',
            'email' => '',
            'password' => '',
            'confirm_password' => '',
            'country' => '',

        ];
        $which = 'in';
        if (!$this->request->isPost()) {
            if($auth_service->hasRememberMe()) {
                return $auth_service->loginWithRememberMe();
            }
        } else {
            $which = $this->request->getPost('which_form');
            $form = "sign_{$which}_form";
            if ($$form->isValid($this->request->getPost()) == false) {
                $messages = $$form->getMessages(true);
                /**
                 * @var string $field
                 * @var Message\Group $field_messages
                 */
                foreach($messages as $field => $field_messages) {
                    $errors[] = $field_messages[0]->getMessage();
                    $var = $which.'_form_errors';
                    ${$var}[$field] = ' error';
                }
            } else {
                switch ($which) {
                    case 'in':
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
                        break;
                    case 'up':

                        break;
                }
            }
        }
        $this->view->setVar('which_form', $which);
        $this->view->pick('sign-in/index');
        return $this->view->setVars(['signinform' => $sign_in_form,'signupform' => $sign_up_form, 'errors' => $errors, 'in_form_errors' => $in_form_errors, 'up_form_errors' => $up_form_errors]);
    }
}