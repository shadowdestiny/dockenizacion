<?php
namespace EuroMillions\web\controllers;

use EuroMillions\shared\helpers\SiteHelpers;
use EuroMillions\shared\services\SiteConfigService;
use EuroMillions\web\components\TrackingCodesHelper;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\forms\SignInForm;
use EuroMillions\web\forms\SignUpForm;
use EuroMillions\web\services\AuthService;
use EuroMillions\web\services\BlogService;
use EuroMillions\web\services\CartService;
use EuroMillions\web\services\ChristmasService;
use EuroMillions\web\services\CurrencyConversionService;
use EuroMillions\web\services\CurrencyService;
use EuroMillions\web\services\factories\OrderFactory;
use EuroMillions\web\services\LanguageService;
use EuroMillions\web\services\LotteryService;
use EuroMillions\web\services\PowerBallCartService;
use EuroMillions\web\services\TransactionService;
use EuroMillions\web\services\UserPreferencesService;
use EuroMillions\web\services\UserService;
use EuroMillions\web\vo\Discount;
use EuroMillions\web\vo\dto\OrderPaymentProviderDTO;
use EuroMillions\web\vo\dto\PlayConfigCollectionDTO;
use EuroMillions\web\vo\Order;
use Money\Currency;
use Money\Money;
use Phalcon\Validation\Message;


//TODO: Extract to new parent class
class CartController extends PublicSiteControllerBase
{
    const IP_DEFAULT = '127.0.0.1';

    protected $eventsManager;

    public function initialize(LotteryService $lotteryService = null,
                           LanguageService $languageService = null,
                           CurrencyService $currencyService = null,
                           UserService $userService = null,
                           AuthService $authService = null,
                           UserPreferencesService $userPreferencesService = null,
                           SiteConfigService $siteConfigService = null,
                           CartService $cartService = null,
                           CurrencyConversionService $currencyConversionService = null,
                           TransactionService $transactionService = null,
                           ChristmasService $christmasService = null,
                           BlogService $blogService = null,
                           PowerBallCartService $powerBallCartService = null)
    {
        parent::initialize($lotteryService, $languageService, $currencyService, $userService, $authService, $userPreferencesService, $siteConfigService, $cartService, $currencyConversionService, $transactionService, $christmasService, $blogService, $powerBallCartService); // TODO: Change the autogenerated stub
        $this->eventsManager = new \Phalcon\Events\Manager();
    }

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
        /** @var AuthService $user */
        $user = $this->authService->getCurrentUser();
        if($this->authService->isLogged()) {
            if($this->authService->getLoggedUser()->getValidated()) {
                $this->response->redirect('/'.$this->lottery.'/order');
            } else {
                $this->flash->error($this->languageService->translate('signup_emailconfirm') . '<br>'  . $this->languageService->translate('signup_emailresend'));
                $this->response->redirect('/'.$this->lottery.'/play');
            }
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
                    'phone_number' => $this->request->getPost('prefix')."-".$this->request->getPost('phone'),
                    'birth_date' => $this->request->getPost('year').'-'.$this->request->getPost('month').'-'.$this->request->getPost('day'),
                    'ipaddress' => !empty($this->request->getClientAddress(true)) ? $this->request->getClientAddress(true) : self::IP_DEFAULT,
                ], $user->getId());
                if($result->success()){
                    $this->flash->error($this->languageService->translate('signup_emailconfirm') . '<br>'  . $this->languageService->translate('signup_emailresend'));
                    TrackingCodesHelper::trackingAffiliatePlatformCodeWhenUserIsRegistered();
                    $this->response->redirect('/'.$this->lottery.'/play/?register=user');
                    //$this->response->redirect('/'.$this->lottery.'/order');
                }else{
                    $errors [] = $result->errorMessage();
                }
            }
        }

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
                $userCheck = $this->authService->check([
                    'email'    => $this->request->getPost('email'),
                    'password' => $this->request->getPost('password'),
                    false,
                    'ipaddress' => !empty($this->request->getClientAddress(true)) ? $this->request->getClientAddress(true) : self::IP_DEFAULT,
                ], 'string');

                if (!$userCheck['bool']) {
                    if ($userCheck['error'] == 'disabledUser') {
                        $errors[] = $this->languageService->translate('signin_msg_closed');
                    } else {
                        $errors[] = 'Incorrect email or password.';
                    }
                } else {
                    if($this->authService->isLogged()) {
                        if($this->authService->getLoggedUser()->getValidated()) {
                            return $this->response->redirect('/'.$this->lottery.'/order?user='.$user->getId());
                        }
                        $this->flash->error($this->languageService->translate('signup_emailconfirm') . '<br>'  . $this->languageService->translate('signup_emailresend'));
                        $this->response->redirect('/'.$this->lottery.'/play');
                    }
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

    public function iframeReloadAction()
    {

        $this->noRender();
        try {
            $userCurrency=$this->userPreferencesService->getCurrency();
            $user_id = $this->authService->getCurrentUser()->getId();
            /** @var User $user */
            $user = $this->userService->getUser($user_id);
            $lottery = strtolower($this->request->getPost("lottery"));
            $isWallet = $this->request->getPost('wallet') == 'true' ? true : false;
            $transactionID = $this->request->getPost('tsid');
            $cartService = $this->cartService->get($user_id,$lottery);
            /** @var Order $order */
            $order = $cartService->getValues();
            $order = OrderFactory::create($order->getPlayConfig(), $order->getSingleBetPrice(), $order->getFee(), $order->getFeeLimit(), $order->getDiscount(),$order->getLottery(), $order->getNextDraw(),$isWallet);
            $orderDataToPaymentProvider = new OrderPaymentProviderDTO( [
                'user' => $user,
                'total' => $order->getTotal()->getAmount(),
                'currency' => $userCurrency->getName(),
                'lottery' => $lottery,
                'isWallet' => (bool) $isWallet,
                'isMobile' => SiteHelpers::detectDevice()
            ],
                $this->di->get('config')
            );
//            $this->paymentProviderService->setEventsManager($this->eventsManager);
//            $this->eventsManager->attach('orderservice', $this->orderService);
            $cashierViewDTO = $this->paymentProviderService->getCashierViewDTOFromMoneyMatrix($this->cartPaymentProvider,$orderDataToPaymentProvider,$transactionID);
            //$this->paymentProviderService->createOrUpdateDepositTransactionWithPendingStatus($order,$user,$order->getTotal(),$transactionID);
            $this->cartService->store($order);
            echo json_encode($cashierViewDTO);
        } catch (\Exception $e)
        {
            echo json_encode([
                'errorMsg' => $e->getMessage()
            ]);
        }
    }

    /**
     * @return SignUpForm
     */
    private function getSignUpForm()
    {
        return SiteHelpers::getSignUpForm();
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
            'accept' => ''
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

    //TODO refactor this function, shit!!
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
        $single_bet_price = $this->domainServiceFactory->getLotteryService()->getSingleBetPriceByLottery($this->lottery);
        $lottery = $this->domainServiceFactory->getLotteryService()->getLotteryConfigByName($this->lottery);
        $user = $this->authService->getCurrentUser();
        $discount = new Discount($result->returnValues()[0]->getFrequency(), $this->domainServiceFactory->getPlayService()->getBundleDataAsArray());
        /** @var PlayConfig[] $play_config */
        $play_config_collection = $result->returnValues();
        $play_config_dto = new PlayConfigCollectionDTO($play_config_collection, $single_bet_price);
        $play_config_dto->single_bet_price_converted = $this->currencyConversionService->convert($play_config_dto->single_bet_price, $user_currency);
        $play_config_dto->singleBetPriceWithDiscountConverted = $this->currencyConversionService->convert($play_config_dto->singleBetPriceWithDiscount, $user_currency);
        $wallet_balance = $this->currencyConversionService->convert($play_config_dto->wallet_balance_user, $user_currency);
        $checked_wallet = ($wallet_balance->getAmount() && !$this->request->isPost()) > 0 ? true : false;

        if ($orderView) {
            $draw = $this->lotteryService->getNextDateDrawByLottery($this->lottery);
            $this->lotteryService->getNextDateDrawByLottery($lottery->getName());
            $order = OrderFactory::create($result->returnValues(), $single_bet_price, $fee_value, $fee_to_limit_value, $discount,$lottery, $draw, $checked_wallet);
            $order_eur = OrderFactory::create($result->returnValues(), $single_bet_price, $this->siteConfigService->getFee(), $this->siteConfigService->getFeeToLimitValue(), $discount,$lottery,$draw, $checked_wallet);
            $this->cartService->store($order);
        }

        //convert to user currency
        $total_price = ($this->currencyConversionService->convert($play_config_dto->singleBetPriceWithDiscount, $user_currency)->getAmount() * $play_config_dto->numPlayConfigs) * $play_config_dto->frequency;
        $symbol_position = $this->currencyConversionService->getSymbolPosition($locale, $user_currency);
        $currency_symbol = $this->currencyConversionService->getSymbol($wallet_balance, $locale);
        $ratio = $this->currencyConversionService->getRatio(new Currency('EUR'), $user_currency);
        $this->tag->prependTitle('Review and Buy');


        $orderDataToPaymentProvider = new OrderPaymentProviderDTO( [
                'user' => $user,
                'total' => $order_eur->getCreditCardCharge()->getFinalAmount()->getAmount(),
                'currency' => $user_currency->getName(),
                'lottery' => $this->lottery,
                'isWallet' => $checked_wallet,
                'isMobile' => SiteHelpers::detectDevice()
             ],
            $this->di->get('config')
        );
        $cashierViewDTO = $this->paymentProviderService->getCashierViewDTOFromMoneyMatrix($this->cartPaymentProvider,$orderDataToPaymentProvider);
        if($this->cartPaymentProvider->type() == 'IFRAME' && $cashierViewDTO->transactionID != null)
        {
           //$this->paymentProviderService->createOrUpdateDepositTransactionWithPendingStatus($order,$this->userService->getUser($user->getId()),$order_eur->getCreditCardCharge()->getFinalAmount(),$cashierViewDTO->transactionID);
        }


        return $this->view->setVars([
            'order' => $play_config_dto,
            'config' => json_encode(
                [
                    'duration' => $play_config_dto->duration,
                    'frequency' => $play_config_dto->frequency,
                    'draw_days' => $play_config_dto->drawDays,
                    'startDrawDate' => $play_config_dto->startDrawDate,
                    'startDrawDateFormat' => $play_config_dto->startDrawDateTime->format($this->languageService->translate('dateformat'))
                ]
            ),
            'cashier' => $cashierViewDTO,
            'wallet_balance' => $wallet_balance->getAmount() / 100,
            'total_price' => $total_price / 100,
            'form_errors' => $formErrors,
            'ratio' => $ratio,
            'singleBetPriceWithDiscount' => $play_config_dto->singleBetPriceWithDiscount->getAmount(),
            'fee_limit' => $fee_to_limit_value->getAmount() / 100,
            'fee' => $fee_value->getAmount() / 100,
            'discount' => $discount->getValue(),
            'currency_symbol' => $currency_symbol,
            'symbol_position' => ($symbol_position === 0) ? false : true,
            'message' => (!empty($msg)) ? $msg : '',
            'errors' => $errors,
            'show_form_credit_card' => (!empty($errors)) ? true : false,
            'msg' => [],
            'checked_wallet' => $checked_wallet,
            'email' => $user->getEmail()->toNative(),
            'total_new_payment_gw' => isset($order_eur) ? $order_eur->getTotal()->getAmount() / 100 : '',
            'credit_card_form' => $creditCardForm,
            'emerchant_data' => $this->getEmerchantData(),
            'power_play' => method_exists($order,'getPowerPlay') ? $order->getPowerPlay() : false,
            'power_play_price' => $this->domainServiceFactory->getPlayService()->getPowerPlay(),
            'lottery_name' => ucfirst($this->lottery),
        ]);
    }

    /**
     * @return array
     */
    private function getEmerchantData()
    {
        $thm_org_id = 'lygdph9h';
        $client_id = "909524";
        $thm_session_id = $client_id . date('Ymdhis') . rand(100000, 999999);

        return [
            'thm_org_id' => 'lygdph9h',
            'client_id' => "909524",
            'thm_session_id' => $thm_session_id,
            'thm_guid' => md5(rand()),
            'thm_params' => 'org_id=' . $thm_org_id . '&session_id=' . $thm_session_id
        ];
    }

}
