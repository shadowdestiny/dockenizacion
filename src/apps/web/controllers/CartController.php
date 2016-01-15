<?php
namespace EuroMillions\web\controllers;

use Captcha\Captcha;
use EuroMillions\web\components\ReCaptchaWrapper;
use EuroMillions\web\entities\GuestUser;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\forms\ForgotPasswordForm;
use EuroMillions\web\forms\MyAccountForm;
use EuroMillions\web\forms\SignInForm;
use EuroMillions\web\forms\SignUpForm;
use EuroMillions\web\vo\dto\UserDTO;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\ActionResult;
use EuroMillions\web\vo\UserId;
use Money\Currency;
use Money\Money;
use Phalcon\Validation\Message;

/** WARNING: THIS CONTROLLER HAS BEEN CLONED FROM THE USERACCESS CONTROLLER JUST SO ALESSIO CAN WORK ON THE DESIGN
 *  THE FUNCTIONALITY IS NOT THE REAL ONE
 */

class CartController extends PublicSiteControllerBase{

    //EMTD should we create an entity to load config global data system?
    private static $_config_vars = [
        'fee_below' => 1200,
        'fee_below_currency' => 'EUR',
        'fee_charge' => 35,
        'fee_charge_currency' => 'EUR'
    ];


    public function orderAction(){

        $user_id = $this->request->get('user');
        /** @var UserId $currenct_user_id */
        $current_user_id = $this->authService->getCurrentUser()->getId();
        // for the moment user_id is a guest_user
        if(!empty($user_id)) {
            $user = new User();
            $user->setId(new UserId($user_id));
            $result_guest_user = $this->domainServiceFactory->getPlayService()->getPlaysFromTemporarilyStorage($user);
            if($result_guest_user->success()) {
               $json_play = $this->domainServiceFactory->getPlayService()->getPlaysFromTemporarilyStorage($user);
               $this->domainServiceFactory->getPlayService()->savePlayFromJson($json_play->getValues()->getValues(),$current_user_id);
                $user = $this->userService->getUser($current_user_id);
                $result = $this->domainServiceFactory->getPlayService()->getPlaysFromTemporarilyStorage($user);
            }
        } else {
            /** @var User $user */
            $user = $this->userService->getUser($current_user_id);
            $result = $this->domainServiceFactory->getPlayService()->getPlaysFromTemporarilyStorage($user);
        }
        $price_single_bet = $this->lotteriesDataService->getSingleBetPriceByLottery('EuroMillions');
        $play_config_json = '';
        $bet_price_value_currency = $this->currencyService->convert($price_single_bet,$user->getUserCurrency());
        $fee_below = $this->currencyService->convert(new Money(self::$_config_vars['fee_below'],new Currency(self::$_config_vars['fee_below_currency'])), $user->getUserCurrency());
        $fee_charge = $this->currencyService->convert(new Money(self::$_config_vars['fee_charge'],new Currency(self::$_config_vars['fee_charge_currency'])), $user->getUserCurrency());
        $wallet_balance = $this->currencyService->convert($user->getBalance(),$user->getUserCurrency());
        if($result->success()) {
            /** @var ActionResult $play_config_json */
            $play_config_json = $result->getValues();
            $play_config_decode = json_decode($play_config_json->getValues());
            $total_price = count($play_config_decode->euroMillionsLines->bets)
                * $play_config_decode->drawDays * $bet_price_value_currency->getAmount() *  $play_config_decode->frequency / 10000;
        } else {
            $msg = 'Error trying get data';
        }
        $currency_symbol = $this->currencyService->getSymbol($bet_price_value_currency,$user->getBalance()->getCurrency());

        return $this->view->setVars([
            'total' => $total_price,
            'currency_symbol' => $currency_symbol,
            'fee_below' => $fee_below->getAmount() / 100,
            'fee_charge' => $fee_charge->getAmount() / 100,
            'single_bet_price' => $bet_price_value_currency->getAmount() / 10000,
            'wallet_balance' => $wallet_balance->getAmount() / 100,
            'play_config_list' => $play_config_json->getValues(),
            'message' => (!empty($msg)) ? $msg : ''
        ]);
    }

    public function successAction(){}
    public function failAction(){}

    public function indexAction($paramsFromPreviousAction = null)
    {
        $errors = null;
        $sign_in_form = new SignInForm();
        $form_errors = $this->getErrorsArray();
        $sign_up_form = $this->getSignUpForm();
        list($controller, $action, $params) = $this->getPreviousParams($paramsFromPreviousAction);

//        if ($this->request->isPost()) {
//            if ($sign_in_form->isValid($this->request->getPost()) == false) {
//                $messages = $sign_in_form->getMessages(true);
//                /**
//                 * @var string $field
//                 * @var Message\Group $field_messages
//                 */
//                foreach ($messages as $field => $field_messages) {
//                    $errors[] = $field_messages[0]->getMessage();
//                    $form_errors[$field] = ' error';
//                }
//            } else {
//                if (!$this->authService->check([
//                    'email'    => $this->request->getPost('email'),
//                    'password' => $this->request->getPost('password'),
//                    'remember' => $this->request->getPost('remember'),
//                ], 'string')
//                ) {
//                    $errors[] = 'Email/password combination not valid';
//                } else {
//                    return $this->response->redirect("$controller/$action".implode('/',$params));
//                }
//            }
//        }
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
        $user = $this->authService->getCurrentUser();
        if($user instanceof User) {
            $this->response->redirect('cart/order');
        }
        $sign_up_form = $this->getSignUpForm();
        list($controller, $action, $params) = $this->getPreviousParams($paramsFromPreviousAction);
        $sign_in_form = new SignInForm();
        $myaccount_form = $this->getMyACcountForm();
        $form_errors = $this->getErrorsArray();
        if($this->request->isPost()) {
            if ($myaccount_form->isValid($this->request->getPost()) == false) {
                $messages = $myaccount_form->getMessages(true);

                foreach ($messages as $field => $field_messages) {
                    $errors[] = $field_messages[0]->getMessage();
                    $form_errors[$field] = ' error';
                }
            }else {
                $result = $this->authService->registerFromCheckout([
                    'name'     => $this->request->getPost('name'),
                    'surname'  => $this->request->getPost('surname'),
                    'password' => $this->request->getPost('password'),
                    'email'    => $this->request->getPost('email'),
                    'country'  => $this->request->getPost('country'),
                ], $user->getId());
                if($result->success()){
                    $this->response->redirect('cart/order');
                    $msg = $result->getValues();
                }else{
                    $errors [] = $result->errorMessage();
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

    public function loginAction($paramsFromPreviousAction = null)
    {
        $errors = null;
        $sign_in_form = new SignInForm();
        $form_errors = $this->getErrorsArray();
        $sign_up_form = $this->getSignUpForm();
        $userId = $this->authService->getCurrentUser();
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
                    return $this->response->redirect('cart/order?user='.$userId->getId());
                }
            }
        }
        $this->view->pick('cart/profile');
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
        $geoService = $this->domainServiceFactory->getServiceFactory()->getGeoService();
        $countries = $geoService->countryList();
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

    private function getMyACcountForm($userId = null)
    {
        $geoService = $this->domainServiceFactory->getServiceFactory()->getGeoService();
        $countries = $geoService->countryList();
        $countries = array_combine(range(1, count($countries)), array_values($countries));
        if( $userId == null ) {
            $myaccount_form = new MyAccountForm(null,['countries' => $countries]);
        } else {
            $user = $this->userService->getUser($userId->getId());
            $user_dto = new UserDTO($user);
            $myaccount_form = new MyAccountForm($user_dto,['countries' => $countries]);
        }
        sort($countries);
        return $myaccount_form;
    }


}
