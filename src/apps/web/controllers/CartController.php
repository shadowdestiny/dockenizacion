<?php
namespace EuroMillions\web\controllers;

use EuroMillions\web\entities\User;
use EuroMillions\web\forms\CreditCardForm;
use EuroMillions\web\forms\MyAccountForm;
use EuroMillions\web\forms\SignInForm;
use EuroMillions\web\forms\SignUpForm;
use EuroMillions\web\vo\dto\UserDTO;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\UserId;
use Money\Currency;
use Money\Money;
use Phalcon\Validation\Message;


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
        $credit_card_form = new CreditCardForm();
        $form_errors = $this->getErrorsArray();

        // for the moment user_id is a guest_user
        $result = false;
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
        //EMTD: @rmrbest move to a service method to get total price
        $price_single_bet = $this->lotteriesDataService->getSingleBetPriceByLottery('EuroMillions');
        $play_config_json = '';
        $bet_price_value_currency = $this->currencyService->convert($price_single_bet,$user->getUserCurrency());
        $fee_below = $this->currencyService->convert(new Money(self::$_config_vars['fee_below'],new Currency(self::$_config_vars['fee_below_currency'])), $user->getUserCurrency());
        $fee_charge = $this->currencyService->convert(new Money(self::$_config_vars['fee_charge'],new Currency(self::$_config_vars['fee_charge_currency'])), $user->getUserCurrency());
        $wallet_balance = $this->currencyService->convert($user->getBalance(),$user->getUserCurrency());
        if($result->success()) {
            //EMTD check this redirect
            if(!$result->getValues()->success()) {
                $this->response->redirect('/play');
            }
            /** @var ActionResult $play_config_json */
            $play_config_json = $result->getValues();
            $play_config_decode = json_decode($play_config_json->getValues());
            $total_price = count($play_config_decode->euroMillionsLines->bets)
                * $play_config_decode->drawDays * $bet_price_value_currency->getAmount() *  $play_config_decode->frequency / 10000;
        } else {
            $this->response->redirect('/play');
        }

        $currency_symbol = $this->currencyService->getSymbol($bet_price_value_currency,$user->getBalance()->getCurrency());
        $locale = $this->request->getBestLanguage();
        $symbol_position = $this->currencyService->getSymbolPosition($locale,$user->getUserCurrency());
        return $this->view->setVars([
            'total' => !empty($total_price) ? $total_price : 0,
            'form_errors' => $form_errors,
            'currency_symbol' => $currency_symbol,
            'symbol_position' => ($symbol_position === 0) ? false : true,
            'fee_below' => $fee_below->getAmount() / 100,
            'fee_charge' => $fee_charge->getAmount() / 100,
            'single_bet_price' => $bet_price_value_currency->getAmount() / 10000,
            'wallet_balance' => $wallet_balance->getAmount() / 100,
            'play_config_list' => $play_config_json->getValues(),
            'message' => (!empty($msg)) ? $msg : '',
            'errors' => [],
            'msg' => [],
            'credit_card_form' => $credit_card_form
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
        $errors = [];
        $user = $this->authService->getCurrentUser();
        if($user instanceof User) {
            $this->response->redirect('/cart/order');
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
                    $this->response->redirect('/cart/order');
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
        $errors = [];
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
                    false,
                ], 'string')
                ) {
                    $errors[] = 'Email/password combination not valid';
                } else {
                    return $this->response->redirect('/cart/order?user='.$userId->getId());
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


    public function paymentAction()
    {

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
        return new SignUpForm(null, ['countries' => $countries]);
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

    private function getMyACcountForm($userId = null)
    {
        $geoService = $this->domainServiceFactory->getServiceFactory()->getGeoService();
        $countries = $geoService->countryList();
        $countries = array_combine(range(1, count($countries)), array_values($countries));
        if( $userId == null ) {
            $myaccount_form = new MyAccountForm(null,['countries' => $countries]);
            $myaccount_form->addPasswordElement();
        } else {
            /** @var User $user */
            $user = $this->userService->getUser($userId->getId());
            $user_dto = new UserDTO($user);
            $myaccount_form = new MyAccountForm($user_dto,['countries' => $countries]);
        }
        sort($countries);
        return $myaccount_form;
    }

}
