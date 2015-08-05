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
        $form_errors = $this->getErrorsArray();

        $sign_up_form = $this->getSignUpForm();

        if (!$this->request->isPost()) {
            if ($auth_service->hasRememberMe()) {
                return $auth_service->loginWithRememberMe();
            }
        } else {
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
                if (!$auth_service->check([
                    'email'    => $this->request->getPost('email'),
                    'password' => $this->request->getPost('password'),
                    'remember' => $this->request->getPost('remember'),
                ], 'string')
                ) {
                    $errors[] = 'Email/password combination not valid';
                } else {
                    //EMTD send email meesage to new user.
                    $this->redirectToPreviousAction($paramsFromPreviousAction);
                }
            }
        }
        $this->view->pick('sign-in/index');
        return $this->view->setVars([
            'which_form'  => 'in',
            'signinform'  => $sign_in_form,
            'signupform'  => $sign_up_form,
            'errors'      => $errors,
            'form_errors' => $form_errors,
        ]);
    }

    public function signUpAction($paramsFromPreviousAction = null)
    {
        $errors = null;
        $auth_service = $this->domainServiceFactory->getAuthService();
        $sign_in_form = new SignInForm();
        $form_errors = $this->getErrorsArray();
        $sign_up_form = $this->getSignUpForm();
        if ($this->request->isPost()) {
            if ($sign_up_form->isValid($this->request->getPost()) == false) {
                $messages = $sign_up_form->getMessages(true);
                /**
                 * @var string $field
                 * @var Message\Group $field_messages
                 */
                foreach ($messages as $field => $field_messages) {
                    $errors[] = $field_messages[0]->getMessage();
                    $form_errors[$field] = ' error';
                }
            } else {
                $register_result = $auth_service->register([
                    'name'             => $this->request->getPost('name'),
                    'surname'          => $this->request->getPost('surname'),
                    'email'            => $this->request->getPost('email'),
                    'password'         => $this->request->getPost('password'),
                    'country'          => $this->request->getPost('country'),
                ]);
                if (!$register_result->success()) {
                    $errors[] = $register_result->errorMessage();
                } else {
                    $this->redirectToPreviousAction($paramsFromPreviousAction);
                }

                var_dump($this->request->getPost());
                $this->noRender();
            }
        }
        $this->view->pick('sign-in/index');
        return $this->view->setVars([
            'which_form'  => 'up',
            'signinform'  => $sign_in_form,
            'signupform'  => $sign_up_form,
            'errors'      => $errors,
            'form_errors' => $form_errors,
        ]);
    }

    /**
     * @param $paramsFromPreviousAction
     */
    private function redirectToPreviousAction($paramsFromPreviousAction)
    {
        $dispatcher = $this->getDI()->get('dispatcher');
        $controller = $dispatcher->getPreviousControllerName();
        $action = $dispatcher->getPreviousActionName();
        $redirect = "$controller/$action/$paramsFromPreviousAction";
        $this->response->redirect($redirect);
    }

    /**
     * @return SignUpForm
     */
    private function getSignUpForm()
    {
        $gs = $this->domainServiceFactory->getGeoService();
        $countries = $gs->countryList();
        $sign_up_form = new SignUpForm(null, ['countries' => $countries]);
        return $sign_up_form;
    }

    /**
     * @return array
     */
    private function getErrorsArray()
    {
        $form_errors = [
            'email'            => '',
            'password'         => '',
            'name'             => '',
            'surname'          => '',
            'confirm_password' => '',
            'country'          => '',

        ];
        return $form_errors;
    }
}