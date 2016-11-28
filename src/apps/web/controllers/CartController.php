<?php
namespace EuroMillions\web\controllers;

use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\forms\SignInForm;
use EuroMillions\web\forms\SignUpForm;
use EuroMillions\web\vo\dto\PlayConfigCollectionDTO;
use EuroMillions\web\vo\Order;
use Money\Currency;
use Phalcon\Validation\Message;


class CartController extends PublicSiteControllerBase
{
    const IP_DEFAULT = '127.0.0.1';

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
            $this->response->redirect('/'.$this->lottery.'/order');
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
                    'ipaddress' => !empty($this->request->getClientAddress()) ? $this->request->getClientAddress() : self::IP_DEFAULT,
                ], $user->getId());
                if($result->success()){
                    $this->response->redirect('/'.$this->lottery.'/order');
                }else{
                    $errors [] = $result->errorMessage();
                }
            }
        }

        $this->view->pick('cart/profile');
	    $this->tag->prependTitle('Log In or Sign Up');
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
                    'ipaddress' => !empty($this->request->getClientAddress()) ? $this->request->getClientAddress() : self::IP_DEFAULT,
                ], 'string')
                ) {
                    $errors[] = 'Incorrect email or password.';
                } else {
                    return $this->response->redirect('/'.$this->lottery.'/order?user='.$user->getId());
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
    protected function getErrorsArray()
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
    protected function dataOrderView($user,
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
        $user = $this->authService->getCurrentUser();
        if($orderView) {
            $order = new Order($result->returnValues(),$single_bet_price, $fee_value, $fee_to_limit_value); // order created
            $order_eur = new Order($result->returnValues(),$single_bet_price, $this->siteConfigService->getFee(), $this->siteConfigService->getFeeToLimitValue()); //workaround for new payment gateway
            $this->cartService->store($order);
        }
        /** @var PlayConfig[] $play_config */
        $play_config_collection = $result->returnValues();
        $play_config_dto = new PlayConfigCollectionDTO($play_config_collection, $single_bet_price);
        $wallet_balance = $this->currencyConversionService->convert($play_config_dto->wallet_balance_user, $user_currency);
        $checked_wallet = ($wallet_balance->getAmount() && !$this->request->isPost()) > 0 ? true : false;
        $play_config_dto->single_bet_price_converted = $this->currencyConversionService->convert($play_config_dto->single_bet_price, $user_currency);
        //convert to user currency
        $total_price = $this->currencyConversionService->convert($play_config_dto->single_bet_price, $user_currency)->getAmount() * $play_config_dto->numPlayConfigs;
        $symbol_position = $this->currencyConversionService->getSymbolPosition($locale, $user_currency);
        $currency_symbol = $this->currencyConversionService->getSymbol($wallet_balance, $locale);
        $ratio = $this->currencyConversionService->getRatio(new Currency('EUR'), $user_currency);
	    $this->tag->prependTitle('Review and Buy');
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
            'email'             => $user->getEmail()->toNative(),
            'total_new_payment_gw' => isset($order_eur) ? $order_eur->getTotal()->getAmount() / 100 : '',
            'credit_card_form' => $creditCardForm
        ]);
    }


}
