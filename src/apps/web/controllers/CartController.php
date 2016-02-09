<?php
namespace EuroMillions\web\controllers;

use EuroMillions\web\entities\User;
use EuroMillions\web\forms\CreditCardForm;
use EuroMillions\web\forms\MyAccountForm;
use EuroMillions\web\forms\SignInForm;
use EuroMillions\web\forms\SignUpForm;
use EuroMillions\web\vo\dto\OrderDTO;
use EuroMillions\web\vo\dto\SiteConfigDTO;
use EuroMillions\web\vo\dto\UserDTO;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\Order;
use EuroMillions\web\vo\PlayFormToStorage;
use EuroMillions\web\vo\UserId;
use Money\Currency;
use Money\Money;
use Phalcon\Validation\Message;


class CartController extends PublicSiteControllerBase{

    public function orderAction(){

        $user_id = $this->request->get('user');
        /** @var UserId $currenct_user_id */
        $current_user_id = $this->authService->getCurrentUser()->getId();
        $credit_card_form = new CreditCardForm();
        $form_errors = $this->getErrorsArray();

        if(!empty($user_id)) {
            $result = $this->domainServiceFactory->getPlayService()->getPlaysFromGuestUserAndSwitchUser(new UserId($user_id),$current_user_id);
            $user = $this->userService->getUser($current_user_id);
        } else {
            /** @var User $user */
            $user = $this->userService->getUser($current_user_id);
            $result = $this->domainServiceFactory->getPlayService()->getPlaysFromTemporarilyStorage($user);
        }
        if(!$result->success()) {
            $this->response->redirect('/play');
            return false;
        }

        list($fee,$fee_limit) = $this->getFees();
        $single_bet_price = $this->domainServiceFactory->getLotteriesDataService()->getSingleBetPriceByLottery('EuroMillions');
        $order = new Order($result->returnValues(),$single_bet_price, $fee, $fee_limit); // order created
        //save order in storage
        $result_save_order = $this->domainServiceFactory->getPlayService()->saveOrderToStorage($order);
        if(!$result_save_order->success()) {
            //EMTD redirect with error message
        }
        list($currency_symbol,$bet_price_value_currency, $wallet_balance,$total_price_currency) = $this->getVarsToOrderView($order, $user, $single_bet_price);
        $locale = $this->request->getBestLanguage();
        $symbol_position = $this->currencyService->getSymbolPosition($locale,$order->getPlayConfig()->getUser()->getUserCurrency());
        $order_dto = new OrderDTO($order);
        $order_dto->setSingleBetPrice($bet_price_value_currency);
        $order_dto->setWalletBalance($wallet_balance);
        $order_dto->setTotal($total_price_currency);

        return $this->view->setVars([
            'order' => $order_dto,
            'form_errors' => $form_errors,
            'currency_symbol' => $currency_symbol,
            'symbol_position' => ($symbol_position === 0) ? false : true,
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
        $credit_card_form = new CreditCardForm();
        $form_errors = $this->getErrorsArray();
        $funds_value = (int) $this->request->getPost('funds-value');
        $card_number = $this->request->getPost('card-number');
        $card_holder_name = $this->request->getPost('card-holder');
        $year = $this->request->getPost('year');
        $month = $this->request->getPost('month');
        $cvv = $this->request->getPost('card-cvv');
        $expiry_date = $this->request->getPost('expiry-date');


        $this->view->pick('cart/profile');
        return $this->view->setVars([
            'which_form'  => 'in',
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
            'expiry-date' => '',

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

    /**
     *
     */
    private function getFees()
    {
        $siteConfig = $this->di->get('siteConfig');
        $fee_site_config_dto = new SiteConfigDTO($siteConfig[0]);
        $fee_to_limit_config_dto = new SiteConfigDTO($siteConfig[1]);
        $fee_value = new Money((int)$fee_site_config_dto->value, new Currency('EUR'));
        $fee_to_limit_value = new Money((int)$fee_to_limit_config_dto->value, new Currency('EUR'));

        return [$fee_value,$fee_to_limit_value];
    }

    /**
     * @param $order
     * @param $user
     * @param $single_bet_price
     * @return array
     * @throws \Exception
     */
    private function getVarsToOrderView(Order $order)
    {
        $user_currency = $order->getPlayConfig()->getUser()->getUserCurrency();
        $total_price = $this->domainServiceFactory->getPlayService()->getTotalPriceFromPlay($order->getPlayConfig(), $order->getSingleBetPrice());
        $total_price_currency = $this->currencyService->convert($total_price, $user_currency);
        $currency_symbol = $this->currencyService->getSymbol($total_price_currency, $order->getPlayConfig()->getUser()->getBalance()->getCurrency());
        $bet_price_value_currency = $this->currencyService->convert($order->getSingleBetPrice(),$user_currency)->getAmount() / 10000;
        $wallet_balance = $this->currencyService->convert($order->getPlayConfig()->getUser()->getBalance(),$order->getPlayConfig()->getUser()->getUserCurrency())->getAmount() / 100;
        $total_price_currency = $this->currencyService->convert($order->getTotal(),$order->getPlayConfig()->getUser()->getUserCurrency())->getAmount() / 10000;
        return array($currency_symbol,$bet_price_value_currency, $wallet_balance,$total_price_currency);
    }

}
