<?php
namespace EuroMillions\controllers;

use Captcha\Captcha;
use EuroMillions\components\ReCaptchaWrapper;
use EuroMillions\forms\ForgotPasswordForm;
use EuroMillions\forms\SignInForm;
use EuroMillions\forms\SignUpForm;
use EuroMillions\services\AuthService;
use EuroMillions\services\GeoService;
use EuroMillions\vo\Email;
use EuroMillions\vo\ActionResult;
use Phalcon\Validation\Message;

/** WARNING: THIS CONTROLLER HAS BEEN CLONED FROM THE USERACCESS CONTROLLER JUST SO ALESSIO CAN WORK ON THE DESIGN
 *  THE FUNCTIONALITY IS NOT THE REAL ONE
 */

class CartController extends PublicSiteControllerBase
{
    /** @var  AuthService */
    protected $authService;
    /** @var  GeoService */
    private $geoService;

    public function initialize(AuthService $authService = null, GeoService $geoService = null)
    {
        parent::initialize();
        $this->authService = $authService ? $authService : $this->domainServiceFactory->getAuthService();
        $this->geoService = $geoService ? $geoService : $this->domainServiceFactory->getServiceFactory()->getGeoService();
    }

    public function indexAction($paramsFromPreviousAction = null)
    {
        $errors = null;
        $sign_in_form = new SignInForm();
        $form_errors = $this->getErrorsArray();

        $sign_up_form = $this->getSignUpForm();

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
                if (!$this->authService->check([
                    'email'    => $this->request->getPost('email'),
                    'password' => $this->request->getPost('password'),
                    'remember' => $this->request->getPost('remember'),
                ], 'string')
                ) {
                    $errors[] = 'Email/password combination not valid';
                } else {
                    return $this->response->redirect("$controller/$action".implode('/',$params));
                }
            }
        }
        $this->view->pick('cart/index');
        return $this->view->setVars([
            'which_form'  => 'in',
            'signinform'  => $sign_in_form,
            'signupform'  => $sign_up_form,
            'errors'      => $errors,
            'form_errors' => $form_errors,
            'controller' => $controller,
            'action' => $action,
            'params' => json_encode($params),
        ]);
    }

    public function profileAction($paramsFromPreviousAction = null)
    {
        $errors = null;
        $sign_in_form = new SignInForm();
        $form_errors = $this->getErrorsArray();
        $sign_up_form = $this->getSignUpForm();
        list($controller, $action, $params) = $this->getPreviousParams($paramsFromPreviousAction);
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
                    return $this->response->redirect("$controller/$action/".implode('/',$params));
                }
            }
        }
        $this->view->pick('cart/profile');
        return $this->view->setVars([
            'which_form'  => 'up',
            'signinform'  => $sign_in_form,
            'signupform'  => $sign_up_form,
            'errors'      => $errors,
            'form_errors' => $form_errors,
            'controller' => $controller,
            'action' => $action,
            'params' => json_encode($params),
        ]);
    }

    public function validateAction($token)
    {
        $current_user = $this->forceLogin($this->authService);
        if(!$current_user) {
            return;
        }
        $result = $this->authService->validateEmailToken($current_user, $token);
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
                $errors[] = 'Invalid email';
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
                        if(empty($reCaptchaResult)) $errors[] = 'You are a robot';
                        $errors[] = $result->errorMessage();
                    }
            }
        }
        $this->view->pick('sign-in/forgot-psw');
        return $this->view->setVars([
            'forgot_password_form' => $forgot_password_form,
            'captcha'              => $captcha->html(),
            'errors'               => $errors,
            'message'              => $message,
        ]);
    }

    public function passwordResetAction($token)
    {
        $result = $this->authService->resetPassword($token);
        if ($result->success()) {
            $message = 'Your password was reset!';
        } else {
            $message = 'Sorry, the token you used is no longer valid.';
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