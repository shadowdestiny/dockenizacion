<?php
namespace EuroMillions\web\controllers;

use Captcha\Captcha;
use EuroMillions\web\components\ReCaptchaWrapper;
use EuroMillions\web\forms\ForgotPasswordForm;
use EuroMillions\web\forms\MyAccountChangePasswordForm;
use EuroMillions\web\forms\ResetPasswordForm;
use EuroMillions\web\forms\SignInForm;
use EuroMillions\web\forms\SignUpForm;
use EuroMillions\web\services\AuthService;
use EuroMillions\web\services\GeoService;
use EuroMillions\web\vo\Email;
use EuroMillions\shared\vo\results\ActionResult;
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
        $this->geoService = $geoService ? $geoService : $this->domainServiceFactory->getServiceFactory()->getGeoService();
    }

    public function signInAction()
    {
        $errors = [];
        $sign_in_form = new SignInForm();
        $form_errors = $this->getErrorsArray();
        $sign_up_form = $this->getSignUpForm();

        $url_redirect = $this->session->get('original_referer');


        if ($this->request->isPost()) {
            if ($sign_in_form->isValid($this->request->getPost()) === false) {
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
                    return $this->response->redirect($url_redirect);
                }
            }
        }

        $this->view->pick('sign-in/index');
        return $this->view->setVars([
            'which_form'  => 'in',
            'signinform'  => $sign_in_form,
            'signupform'  => $sign_up_form,
            'errors'      => $errors,
            'currency_list' => [],
            'time_to_remain_draw' => false,
            'last_minute' => false,
            'draw_date' => '',
            'timeout_to_closing_modal' => 30 * 60 * 1000,
            'minutes_to_close' => false,             
            'form_errors' => $form_errors,
        ]);
    }

    public function signUpAction()
    {
        $errors = [];
        $sign_in_form = new SignInForm();
        $form_errors = $this->getErrorsArray();
        $sign_up_form = $this->getSignUpForm();
        $url_redirect = $this->session->get('original_referer');
        if ($this->request->isPost()) {
            if ($sign_up_form->isValid($this->request->getPost()) === false) {
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
                $credentials = [
                    'name'     => $this->request->getPost('name'),
                    'surname'  => $this->request->getPost('surname'),
                    'email'    => $this->request->getPost('email'),
                    'password' => $this->request->getPost('password'),
                    'country'  => $this->request->getPost('country'),
                ];

                $register_result = $this->authService->register($credentials);

                if (!$register_result->success()) {
                    $errors[] = $register_result->errorMessage();
                } else {
                    return $this->response->redirect($url_redirect);
                }
            }
        }

        $this->view->pick('sign-in/index');
        return $this->view->setVars([
            'which_form'  => 'up',
            'signinform'  => $sign_in_form,
            'signupform'  => $sign_up_form,
            'errors'      => $errors,
            'currency_list' => [],
            'time_to_remain_draw' => false,
            'last_minute' => false,
            'draw_date' => '',
            'timeout_to_closing_modal' => 30 * 60 * 1000,
            'minutes_to_close' => false,             
            'form_errors' => $form_errors,
        ]);
    }

    public function updatePasswordAction()
    {
        $errors = [];
        $form_errors = [];
        $msg = '';
        $userId = $this->authService->getCurrentUser();
        $user = $this->userService->getUser($userId->getId());
        $myaccount_form = $this->getMyACcountForm($userId);
        $myaccount_passwordchange_form = new MyAccountChangePasswordForm();
        if($this->request->isPost()) {
            if ($myaccount_passwordchange_form->isValid($this->request->getPost()) == false) {
                $messages = $myaccount_passwordchange_form->getMessages(true);
                foreach ($messages as $field => $field_messages) {
                    $errors[] = $field_messages[0]->getMessage();
                    $form_errors[$field] = ' error';
                }
            }else {
                $result_same_password = $this->authService->samePassword($user,$this->request->getPost('old-password'));
                if($result_same_password->success()) {
                    $result = $this->authService->updatePassword($user, $this->request->getPost('new-password'));
                    if ($result->success()) {
                        $msg = $result->getValues();
                    } else {
                        $errors [] = $result->errorMessage();
                    }
                }else{
                    $errors[] = $result_same_password->errorMessage();
                }
            }
        }

        return $this->view->setVars([
            'form_errors' => !empty($form_errors) ? $form_errors : [],
            'which_form'  => 'password',
            'errors' => $errors,
            'msg' => !empty($msg) ? $msg : '',
            'myaccount' => $myaccount_form,
            'password_change' => $myaccount_passwordchange_form
        ]);

    }


    public function validateAction($token)
    {
        $result = $this->authService->validateEmailToken($token);
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
        $this->response->redirect('/');
    }


    public function forgotPasswordAction()
    {
        $errors = null;
        $forgot_password_form = new ForgotPasswordForm();

        //get captcha instance
        $config = $this->di->get('globalConfig')['recaptcha'];
        $captcha = new ReCaptchaWrapper(new Captcha());
        $captcha->getCaptcha()->setPublicKey($config['public_key']);
        $captcha->getCaptcha()->setPrivateKey($config['private_key']);

        if ($this->request->isPost()) {
            if ($forgot_password_form->isValid($this->request->getPost()) == false) {
                $errors[] = 'You have inserted something that doesn\'t look like an  email.';
            } else {

                    $email = $this->request->getPost('email');
                    $reCaptchaResult = $captcha->check()->isValid();
                    $result = new ActionResult(false);

                    if(!empty($email) && !empty($reCaptchaResult)){
                        $result = $this->authService->forgotPassword(new Email($email));
                    }
                    if($result->success() && $reCaptchaResult){
                        $message = $result->getValues();
                    } else {
                        if(empty($reCaptchaResult)) $errors[] = 'You are a robot... or you forgot to check the Captcha verification.';
                        $errors[] = $result->errorMessage();
                    }
            }
        }
        $this->view->pick('sign-in/forgot-psw');
        return $this->view->setVars([
            'forgot_password_form' => $forgot_password_form,
            'captcha'              => $captcha->html(),
            'errors'               => $errors,
            'currency_list'        => [],
            'message'              => $message,
        ]);
    }

    public function passwordResetAction($token)
    {
       $result = $this->authService->resetPassword($token);
        if ($result->success()) {
            $message = 'Your password was reset!';
            $this->view->pick('recovery/index');
            return $this->view->setVars([
                'currency_list' => [],
                'token' => $token,
                'message' => false,
                'errors'        => [],
            ]);
        } else {
            //EMTD redirect
            //$message = 'Sorry, the token you used is no longer valid.';
        }
    }


    /**
     * @return SignUpForm
     */
    private function getSignUpForm()
    {
        $countries = $this->geoService->countryList();
        sort($countries);
        //key+1, select element from phalcon need index 0 to set empty value
        $countries = array_combine(range(1, count($countries)), array_values($countries));
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
            'card-number' => '',
            'card-holder' => '',
            'card-cvv' => '',

        ];
        return $form_errors;
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