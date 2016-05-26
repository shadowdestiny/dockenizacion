<?php
namespace EuroMillions\web\controllers;

use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\forms\CreditCardForm;
use EuroMillions\web\forms\SignInForm;
use EuroMillions\web\forms\SignUpForm;
use EuroMillions\web\vo\CardHolderName;
use EuroMillions\web\vo\CardNumber;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\CVV;
use EuroMillions\web\vo\dto\PlayConfigCollectionDTO;
use EuroMillions\web\vo\ExpiryDate;
use EuroMillions\web\vo\Order;
use Money\Currency;
use Money\Money;
use Phalcon\Validation\Message;


class CartController extends PublicSiteControllerBase
{

    public function orderAction(){

        $user_id = $this->request->get('user');
        $current_user_id = $this->authService->getCurrentUser()->getId();
        $credit_card_form = new CreditCardForm();
        $form_errors = $this->getErrorsArray();
        $play_service = $this->domainServiceFactory->getPlayService();
        $msg = '';
        $errors = [];

        if(!empty($user_id)) {
            $result = $play_service->getPlaysFromGuestUserAndSwitchUser($user_id,$current_user_id);
            $user = $this->userService->getUser($current_user_id);
        } else {
            /** @var User $user */
            $user = $this->userService->getUser($current_user_id);
            if(!$user) {
                $this->response->redirect('/'.$this->lottery.'/play');
                return false;
            }
            $result = $play_service->getPlaysFromTemporarilyStorage($user);
        }

        if(!$result->success()) {
            $this->response->redirect('/'.$this->lottery.'/play');
            return false;
        }
        return $this->dataOrderView($user, $result, $form_errors, $msg, $credit_card_form, $errors);
    }

    public function successAction(){}
    public function failAction(){}

    /**
     * @param null $paramsFromPreviousAction
     * @return \Phalcon\Mvc\View
     */
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

    /**
     * @param null $paramsFromPreviousAction
     * @return \Phalcon\Mvc\View
     */
    public function profileAction($paramsFromPreviousAction = null)
    {
        $errors = [];
        $user = $this->authService->getCurrentUser();
        if($user instanceof User) {
            $this->response->redirect('/'.$this->lottery.'/cart/order');
        }
        $sign_up_form = $this->getSignUpForm();
        list($controller, $action, $params) = $this->getPreviousParams($paramsFromPreviousAction);
        $sign_in_form = new SignInForm();
        $form_errors = $this->getErrorsArray();
        if($this->request->isPost()) {
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
            }else {
                $result = $this->authService->registerFromCheckout([
                    'name'     => $this->request->getPost('name'),
                    'surname'  => $this->request->getPost('surname'),
                    'password' => $this->request->getPost('password'),
                    'email'    => $this->request->getPost('email'),
                    'country'  => $this->request->getPost('country'),
                ], $user->getId());
                if($result->success()){
                    $this->response->redirect('/'.$this->lottery.'/cart/order');
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

    /**
     * @param null $paramsFromPreviousAction
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface|\Phalcon\Mvc\View
     */
    public function loginAction($paramsFromPreviousAction = null)
    {
        $errors = [];
        $sign_in_form = new SignInForm();
        $form_errors = $this->getErrorsArray();
        $sign_up_form = $this->getSignUpForm();
        $user = $this->authService->getCurrentUser();
        list($controller, $action, $params) = $this->getPreviousParams($paramsFromPreviousAction);

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
                    false,
                ], 'string')
                ) {
                    $errors[] = 'Incorrect email or password.';
                } else {
                    return $this->response->redirect('/'.$this->lottery.'/cart/order?user='.$user->getId());
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
        $funds_value = $this->request->getPost('funds');
        $card_number = $this->request->getPost('card-number');
        $card_holder_name = $this->request->getPost('card-holder');
        $expiry_date_month = $this->request->getPost('expiry-date-month');
        $expiry_date_year = $this->request->getPost('expiry-date-year') + 2000; //This is Antonio, the first developer at EuroMillions. If something failed horribly and you are looking at this, I'm glad that my code survived for more than 75 years, but also my apologies. We changed from 4 digits years to 2 digits, and the implications in all the validators where so much, and we need to finish to go to production, that I decided to include this ugly hack. Luckily, it will work until after I'm dead and rotten.
        $cvv = $this->request->getPost('card-cvv');
        $payWallet = $this->request->getPost('paywallet') !== 'false';
        $play_service = $this->domainServiceFactory->getPlayService();
        $errors = [];
        $user_id = $this->authService->getCurrentUser()->getId();
        /** @var User $user */
        $user = $this->userService->getUser($user_id);
        $result = $play_service->getPlaysFromTemporarilyStorage($user);
        $msg = '';


        $order_view = true;
        if($this->request->isGet()) {
            $charge = $this->request->get('charge');
            $user = $this->userService->getUser($user_id);
            if( null != $user && isset($charge) ){
                try {
                    $card = null;
                    $amount = new Money((int) $charge, new Currency('EUR'));
                    $result = $play_service->play($user_id,$amount, $card,true);
                    if($result->success()) {
                        $this->response->redirect('/'.$this->lottery.'/result/success');
                        return false;
                    } else {
                        $this->response->redirect('/'.$this->lottery.'/result/failure');
                        return false;
                    }
                } catch (\Exception $e ) {
                    $errors[] = $e->getMessage();
                }
            }
        }

        if($this->request->isPost()) {
            $order_view = false;
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
                        $card = new CreditCard(new CardHolderName($card_holder_name), new CardNumber($card_number) , new ExpiryDate($expiry_date_month.'/'.$expiry_date_year), new CVV($cvv));
                        $amount = new Money((int) str_replace('.','',$funds_value), new Currency('EUR'));
                        $result = $play_service->play($user_id,$amount, $card,$payWallet);
                        if($result->success()) {
                            $this->response->redirect('/'.$this->lottery.'/result/success');
                            return false;
                        } else {
                            $this->response->redirect('/'.$this->lottery.'/result/failure');
                            return false;
                        }
                    } catch (\Exception $e ) {
                        $errors[] = $e->getMessage();
                    }
                }
            }
        }

        $this->view->pick('/cart/order');
        return $this->dataOrderView($user, $result, $form_errors, $msg, $credit_card_form, $errors, $order_view);
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
            'expiry-date-month' => '',
            'expiry-date-year' => '',
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

    //EMTD refactor this function, shit!!
    /**
     * @param $user
     * @param $result
     * @param $formErrors
     * @param $msg
     * @param $creditCardForm
     * @return \Phalcon\Mvc\View
     */
    private function dataOrderView($user,
                                   $result,
                                   $formErrors,
                                   $msg,
                                   $creditCardForm,
                                   $errors,
                                   $orderView = true)
    {


        $locale = $this->request->getBestLanguage();
        $user_currency = $user->getUserCurrency();
        $fee_value = $this->siteConfigService->getFeeValueWithCurrencyConverted($user_currency);
        $fee_to_limit_value = $this->siteConfigService->getFeeToLimitValueWithCurrencyConverted($user_currency);
        $single_bet_price = $this->domainServiceFactory->getLotteryService()->getSingleBetPriceByLottery('EuroMillions');
        if($orderView) {
            $order = new Order($result->returnValues(),$single_bet_price, $fee_value, $fee_to_limit_value); // order created
            $this->cartService->store($order);
        }
        /** @var PlayConfig[] $play_config */
        $play_config_collection = $result->returnValues();
        $play_config_dto = new PlayConfigCollectionDTO($play_config_collection, $single_bet_price);
        $wallet_balance = $this->currencyConversionService->convert($play_config_dto->wallet_balance_user, $user_currency);
        $checked_wallet = $wallet_balance->getAmount() > 0 ? true : false;
        $play_config_dto->single_bet_price_converted = $this->currencyConversionService->convert($play_config_dto->single_bet_price, $user_currency);
        //convert to user currency
        $total_price = $this->currencyConversionService->convert($play_config_dto->single_bet_price, $user_currency)->getAmount() * $play_config_dto->numPlayConfigs;
        $symbol_position = $this->currencyConversionService->getSymbolPosition($locale, $user_currency);
        $currency_symbol = $this->currencyConversionService->getSymbol($wallet_balance, $locale);
        $ratio = $this->currencyConversionService->getRatio(new Currency('EUR'), $user_currency);

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
            'total_price'      => $total_price / 100,
            'form_errors'      => $formErrors,
            'ratio'            => $ratio,
            'fee_limit'        => $fee_to_limit_value->getAmount() / 100,
            'fee'              => $fee_value->getAmount() / 100,
            'currency_symbol'  => $currency_symbol,
            'symbol_position'  => ($symbol_position === 0) ? false : true,
            'message'          => (!empty($msg)) ? $msg : '',
            'errors'           => $errors,
            'show_form_credit_card' => (!empty($errors)) ? true : false,
            'msg'              => [],
            'checked_wallet'   => $checked_wallet,
            'credit_card_form' => $creditCardForm
        ]);
    }

}
