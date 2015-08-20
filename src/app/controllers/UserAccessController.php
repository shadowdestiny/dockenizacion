<?php
namespace EuroMillions\controllers;

use EuroMillions\forms\SignInForm;
use EuroMillions\forms\SignUpForm;
use EuroMillions\services\AuthService;
use EuroMillions\services\GeoService;
use Phalcon\Validation\Message;

class UserAccessController extends ControllerBase
{
    /** @var  AuthService */
    private $authService;
    /** @var  GeoService */
    private $geoService;

    public function initialize(AuthService $authService = null, GeoService $geoService = null)
    {
        parent::initialize();
        $this->authService = $authService ? $authService : $this->domainServiceFactory->getAuthService();
        $this->geoService = $geoService ? $geoService : $this->domainServiceFactory->getGeoService();
    }

    public function signInAction($paramsFromPreviousAction = null)
    {
        $errors = null;
        $sign_in_form = new SignInForm();
        $form_errors = $this->getErrorsArray();

        $sign_up_form = $this->getSignUpForm();

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
                if (!$this->authService->check([
                    'email'    => $this->request->getPost('email'),
                    'password' => $this->request->getPost('password'),
                    'remember' => $this->request->getPost('remember'),
                ], 'string')
                ) {
                    $errors[] = 'Email/password combination not valid';
                } else {
                    return $this->redirectToPreviousAction($paramsFromPreviousAction);
                }
            }
        }
        $this->view->pick('sign-in/index');
        $this->view->setVars([
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
                $register_result = $this->authService->register([
                    'name'     => $this->request->getPost('name'),
                    'surname'  => $this->request->getPost('surname'),
                    'email'    => $this->request->getPost('email'),
                    'password' => $this->request->getPost('password'),
                    'country'  => $this->request->getPost('country'),
                ]);
                if (!$register_result->success()) {
                    $errors[] = $register_result->errorMessage();
                } else {
                    $email_service = $this->domainServiceFactory->getEmailService();
                    $email_service->sendRegistrationMail($register_result->getValues());
                    return $this->redirectToPreviousAction($paramsFromPreviousAction);
                }
            }
        }
        $this->view->pick('sign-in/index');
        $this->view->setVars([
            'which_form'  => 'up',
            'signinform'  => $sign_in_form,
            'signupform'  => $sign_up_form,
            'errors'      => $errors,
            'form_errors' => $form_errors,
        ]);
    }

    public function validateAction($token)
    {
        $this->noRender();
        //EMTD comprobar que el usuario está logueado. Si no, redirigir a login.
        $result = $this->authService->validateEmailToken($this->authService->getCurrentUser(), $token);
        if ($result->success()) {
            $message = 'Thanks! Your email has been validated';
        } else {
            $message = 'Sorry, the token you used is no longer valid. (message was: "'.$result->getValues().'""). Click here to request a new one.'; //EMTD click here has to work and send a new token to the user.
        }
        $this->view->setVar('message', $message);
    }

    public function logoutAction()
    {
        $this->authService->logout();
        $this->response->redirect();
    }

    /**
     * @param $paramsFromPreviousAction
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    private function redirectToPreviousAction($paramsFromPreviousAction)
    {
        $dispatcher = $this->getDI()->get('dispatcher');
        $controller = $dispatcher->getPreviousControllerName();
        $action = $dispatcher->getPreviousActionName();
        $redirect = "$controller/$action/$paramsFromPreviousAction";
        return $this->response->redirect($redirect);
    }

    /**
     * @return SignUpForm
     */
    private function getSignUpForm()
    {
        $countries = $this->geoService->countryList();
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