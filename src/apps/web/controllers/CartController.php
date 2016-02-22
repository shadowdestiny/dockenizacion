<?php
namespace EuroMillions\web\controllers;

use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\forms\CreditCardForm;
use EuroMillions\web\forms\MyAccountForm;
use EuroMillions\web\forms\SignInForm;
use EuroMillions\web\forms\SignUpForm;
use EuroMillions\web\vo\CardHolderName;
use EuroMillions\web\vo\CardNumber;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\CVV;
use EuroMillions\web\vo\dto\OrderDTO;
use EuroMillions\web\vo\dto\PlayConfigDTO;
use EuroMillions\web\vo\dto\UserDTO;
use EuroMillions\web\vo\ExpiryDate;
use EuroMillions\web\vo\Order;
use EuroMillions\web\vo\UserId;
use Money\Currency;
use Money\Money;
use Phalcon\Validation\Message;


class CartController extends PublicSiteControllerBase
{

    public function orderAction(){

        $user_id = $this->request->get('user');
        /** @var UserId $currenct_user_id */
        $current_user_id = $this->authService->getCurrentUser()->getId();
        $credit_card_form = new CreditCardForm();
        $form_errors = $this->getErrorsArray();
        $play_service = $this->domainServiceFactory->getPlayService();
        $msg = '';
        $errors = [];

        if(!empty($user_id)) {
            $result = $play_service->getPlaysFromGuestUserAndSwitchUser(new UserId($user_id),$current_user_id);
            $user = $this->userService->getUser($current_user_id);
        } else {
            /** @var User $user */
            $user = $this->userService->getUser($current_user_id);
            $result = $play_service->getPlaysFromTemporarilyStorage($user);
        }

        if(!$result->success()) {
            $this->response->redirect('/play');
            return false;
        }
        return $this->dataOrderView($user, $result, $form_errors, $msg, $credit_card_form, $errors);
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
        //$myaccount_form = $this->getMyACcountForm();
        $form_errors = $this->getErrorsArray();
        if($this->request->isPost()) {
            if ($sign_up_form->isValid($this->request->getPost()) == false) {
                $messages = $sign_up_form->getMessages(true);
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
            'form_errors_login' => $this->getErrorsArray(),
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
                    $errors[] = 'Incorrect email or password.';
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
            'form_errors' => $this->getErrorsArray(),
            'form_errors_login' => $form_errors,
            'controller' => $controller,
            'action' => $action,
            'params' => json_encode($params),
        ]);
    }


    public function paymentAction()
    {
        $credit_card_form = new CreditCardForm();
        $form_errors = $this->getErrorsArray();
        $funds_value = (int) $this->request->getPost('charge');
        $card_number = $this->request->getPost('card-number');
        $card_holder_name = $this->request->getPost('card-holder');
        $expiry_date = $this->request->getPost('expiry-date');
        $cvv = $this->request->getPost('card-cvv');
        $play_service = $this->domainServiceFactory->getPlayService();
        $errors = [];
        /** @var UserId $currenct_user_id */
        $user_id = $this->authService->getCurrentUser()->getId();
        /** @var User $user */
        $user = $this->userService->getUser($user_id);
        $result = $play_service->getPlaysFromTemporarilyStorage($user);
        $msg = '';


        if($this->request->isGet()) {
            $charge = $this->request->get('charge');
            $user = $this->userService->getUser($user_id);
            if(null != $user ){
                try {
                    $card = null;
                    $amount = new Money((int) $charge, new Currency('EUR'));
                    $result = $play_service->play($user_id,$amount, $card);
                    if($result->success()) {
                        $play_service->removeStorePlay($user_id);
                        $play_service->removeStoreOrder($user_id);
                        $this->view->pick('/cart/success');
                        $order_dto = new OrderDTO($result->getValues());
                        return $this->view->setVars([
                            'order' => $order_dto,
                            'start_draw_date_format' => date('D j M Y',$order_dto->getStartDrawDate()->getTimestamp())
                        ]);
                    } else {
                        $this->response->redirect('/cart/fail');
                        return false;
                    }
                } catch (\Exception $e ) {
                    $errors[] = $e->getMessage();
                }
            }
        }

        if($this->request->isPost()) {
            if ($credit_card_form->isValid($this->request->getPost()) == false ) {
                $messages = $credit_card_form->getMessages(true);
                /**
                 * @var string $field
                 * @var Message\Group $field_messages
                 */
                //check date
                foreach ($messages as $field => $field_messages) {
                    $errors[] = $field_messages[0]->getMessage();
                    $form_errors[$field] = ' error';
                }
            }else {
                /** User $user */
                $user = $this->userService->getUser($user_id);
                if(null != $user ){
                    try {
                        $card = new CreditCard(new CardHolderName($card_holder_name), new CardNumber($card_number) , new ExpiryDate($expiry_date), new CVV($cvv));
                        $amount = new Money((int)$funds_value, new Currency('EUR'));
                        $result = $play_service->play($user_id,$amount, $card);
                        if($result->success()) {
                            $play_service->removeStorePlay($user_id);
                            $play_service->removeStoreOrder($user_id);
                            $this->view->pick('/cart/success');
                            $order_dto = new OrderDTO($result->getValues());
                            return $this->view->setVars([
                               'order' => $order_dto,
                               'start_draw_date_format' => date('D j M Y',$order_dto->getStartDrawDate()->getTimestamp())
                            ]);
                        } else {
                            $this->response->redirect('/cart/fail');
                            return false;
                        }
                    } catch (\Exception $e ) {
                        $errors[] = $e->getMessage();
                    }
                }
            }
        }

        $this->view->pick('/cart/order');
        return $this->dataOrderView($user, $result, $form_errors, $msg, $credit_card_form, $errors, false);
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
     * @param $user
     * @param $result
     * @param $form_errors
     * @param $msg
     * @param $credit_card_form
     * @return \Phalcon\Mvc\View
     */
    private function dataOrderView($user,
                                   $result,
                                   $form_errors,
                                   $msg,
                                   $credit_card_form,
                                   $errors,
                                   $order_view = true)
    {


        $locale = $this->request->getBestLanguage();
        $user_currency = $user->getUserCurrency();
        $fee_value = $this->siteConfigService->getFeeValueWithCurrencyConverted($user_currency);
        $fee_to_limit_value = $this->siteConfigService->getFeeToLimitValueWithCurrencyConverted($user_currency);
        $single_bet_price = $this->domainServiceFactory->getLotteriesDataService()->getSingleBetPriceByLottery('EuroMillions');
        if($order_view) {
            $order = new Order($result->returnValues(),$single_bet_price, $fee_value, $fee_to_limit_value); // order created
            $this->cartService->store($order);
        }
        $single_bet_price_currency = $this->currencyService->convert($single_bet_price, $user_currency);
        /** @var PlayConfig[] $play_config */
        $play_config_collection = $result->returnValues();
        $play_config_dto = new PlayConfigDTO($play_config_collection, $single_bet_price_currency);
        $wallet_balance = $this->currencyService->convert($play_config_dto->wallet_balance_user, $user_currency);
        $checked_wallet = $wallet_balance->getAmount() > 0;
        //convert to user currency
        $total_price = $this->currencyService->convert($play_config_dto->play_config_total_amount, $user_currency);
        $symbol_position = $this->currencyService->getSymbolPosition($locale, $user_currency);
        $currency_symbol = $this->currencyService->getSymbol($wallet_balance, $locale);

        return $this->view->setVars([
            'order'            => $play_config_dto,
            'config'           => json_encode(
                                        [
                                            'duration' => $play_config_dto->duration,
                                            'frequency' => $play_config_dto->frequency,
                                            'draw_days' => $play_config_dto->drawDays,
                                            'startDrawDate' => $play_config_dto->startDrawDate
                                        ]
                                    ),
            'wallet_balance'   => $wallet_balance->getAmount() / 100,
            'total_price'      => $total_price->getAmount() / 100,
            'form_errors'      => $form_errors,
            'fee_limit'        => $fee_to_limit_value->getAmount() / 100,
            'fee'              => $fee_value->getAmount() / 100,
            'currency_symbol'  => $currency_symbol,
            'symbol_position'  => ($symbol_position === 0) ? false : true,
            'message'          => (!empty($msg)) ? $msg : '',
            'errors'           => $errors,
            'show_form_credit_card' => (!empty($errors)) ? true : false,
            'msg'              => [],
            'checked_wallet'   => $checked_wallet,
            'credit_card_form' => $credit_card_form
        ]);
    }

}
