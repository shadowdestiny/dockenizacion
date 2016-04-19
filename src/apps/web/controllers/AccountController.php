<?php


namespace EuroMillions\web\controllers;


use EuroMillions\shared\components\widgets\PaginationWidget;
use EuroMillions\web\entities\GuestUser;
use EuroMillions\web\entities\User;
use EuroMillions\web\forms\BankAccountForm;
use EuroMillions\web\forms\CreditCardForm;
use EuroMillions\web\forms\MyAccountChangePasswordForm;
use EuroMillions\web\forms\MyAccountForm;
use EuroMillions\web\forms\ResetPasswordForm;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\vo\CardHolderName;
use EuroMillions\web\vo\CardNumber;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\CreditCardCharge;
use EuroMillions\web\vo\CVV;
use EuroMillions\web\vo\dto\PlayConfigCollectionDTO;
use EuroMillions\web\vo\dto\TransactionDTO;
use EuroMillions\web\vo\dto\UserDTO;
use EuroMillions\web\vo\dto\UserNotificationsDTO;
use EuroMillions\web\vo\ExpiryDate;
use EuroMillions\web\vo\NotificationValue;
use Money\Currency;
use Money\Money;
use Phalcon\Di;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator\Regex;

class AccountController extends PublicSiteControllerBase
{

    public function beforeExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {
        /** @var DomainServiceFactory $domainServiceFactory */
        $domainServiceFactory = $dispatcher->getDI()->get('domainServiceFactory');
        $user_id = $domainServiceFactory->getAuthService()->getCurrentUser();
        if($user_id instanceof GuestUser) {
            $this->response->redirect('/sign-in');
            return false;
        }
    }

    /**
     * @return \Phalcon\Mvc\View
     */
    public function indexAction()
    {
        $errors = [];
        $form_errors = [];
        $msg = null;
        if (!$this->authService->isLogged()) {
            $this->view->disable();
            return $this->response->redirect('/sign-in');
        }
        $user = $this->authService->getCurrentUser();
        $myaccount_form = $this->getMyACcountForm($user->getId());
        $myaccount_passwordchange_form = new MyAccountChangePasswordForm();
        if($this->request->isPost()) {
            if ($myaccount_form->isValid($this->request->getPost()) == false) {
                $messages = $myaccount_form->getMessages(true);
                /**
                 * @var string $field
                 * @var Message\Group $field_messages
                 */
                foreach ($messages as $field => $field_messages) {
                    $errors[] = $field_messages[0]->getMessage();
                    $form_errors[$field] = ' error';
                }
            }else {
                $result = $this->userService->updateUserData([
                    'name'     => $this->request->getPost('name'),
                    'surname'  => $this->request->getPost('surname'),
                    'street'   => $this->request->getPost('street'),
                    'zip'      => (int) $this->request->getPost('zip'),
                    'city'     => $this->request->getPost('city'),
                    'phone_number' =>(int) $this->request->getPost('phone_number')
                ]);
                if($result->success()){
                    $msg = $result->getValues();
                }else{
                    $errors [] = $result->errorMessage();
                }
            }
        }
        $this->view->pick('account/index');
        return $this->view->setVars([
            'form_errors' => $form_errors,
            'which_form'  => 'index',
            'errors' => $errors,
            'msg' => $msg,
            'myaccount' => $myaccount_form,
            'password_change' => $myaccount_passwordchange_form
        ]);
    }

    public function transactionAction()
    {
        $userId = $this->authService->getCurrentUser();
        $result = $this->transactionService->getTransactionsByUser($userId);
        $transactionDtoCollection = [];
        foreach($result as $transaction) {
            $transactionDtoCollection[] = new TransactionDTO($transaction);
        }

        $page = (!empty($this->request->get('page'))) ? $this->request->get('page') : 1;
        $paginator = $this->getPaginatorAsArray($transactionDtoCollection,10,$page);
        /** @var \Phalcon\Mvc\ViewInterface $paginator_view */
        $paginator_view = (new PaginationWidget($paginator, $this->request->getQuery()))->render();

        return $this->view->setVars([
            'transactionCollection' => $paginator->getPaginate()->items,
            'page' => $page,
            'paginator_view' => $paginator_view
        ]);
    }


    public function passwordAction()
    {
        $userId = $this->authService->getCurrentUser();
        $myaccount_form = $this->getMyACcountForm($userId);
        $myaccount_passwordchange_form = new MyAccountChangePasswordForm();
        return $this->view->setVars([
            'form_errors' => [],
            'which_form'  => 'password',
            'errors' => [],
            'msg' => null,
            'myaccount' => $myaccount_form,
            'password_change' => $myaccount_passwordchange_form
        ]);
    }

    /**
     * @return \Phalcon\Mvc\View
     */
    public function gamesAction()
    {
        $user = $this->authService->getCurrentUser();
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrency($this->lotteryService->getNextJackpot('EuroMillions'));
        $single_bet_price = $this->domainServiceFactory->getLotteryService()->getSingleBetPriceByLottery('EuroMillions');
        $myGames = null;
        $playConfigInactivesDTOCollection = [];
        $message_actives = '';
        $message_inactives = '';

        if(!empty($user)){
            $myGamesActives = $this->userService->getMyActivePlays($user->getId());
            if($myGamesActives->success()){
                $myGames = $myGamesActives->getValues();
                $playConfigDTO = new PlayConfigCollectionDTO($myGames, $single_bet_price);
            }else{
                $message_actives = $myGamesActives->errorMessage();
            }
            $myGamesInactives = $this->userService->getMyInactivePlays($user->getId());
            if($myGamesInactives->success()){
                $playConfigInactivesDTOCollection[] = new PlayConfigCollectionDTO($myGamesInactives->getValues(), $single_bet_price);
            }else{
                $message_inactives = $myGamesInactives->errorMessage();
            }
        }
        $this->view->pick('account/games');
        return $this->view->setVars([
            'my_games_actives' => $playConfigDTO,
            'my_games_inactives' => $playConfigInactivesDTOCollection,
            'jackpot_value' => $jackpot,
            'message_actives' => $message_actives,
            'message_inactives' => $message_inactives
        ]);
    }

    /**
     * @return \Phalcon\Mvc\View
     */
    public function walletAction()
    {
        $credit_card_form = new CreditCardForm();
        $geoService = $this->domainServiceFactory->getServiceFactory()->getGeoService();
        $countries = $geoService->countryList();
        sort($countries);
        $countries = array_combine(range(1, count($countries)), array_values($countries));
        $credit_card_form = $this->appendElementToAForm($credit_card_form);
        $form_errors = $this->getErrorsArray();
        $user_id = $this->authService->getCurrentUser();
        /** @var User $user */
        $user = $this->userService->getUser($user_id->getId());
        $bank_account_form = new BankAccountForm($user, ['countries' => $countries] );
        $site_config_dto = $this->siteConfigService->getSiteConfigDTO($user->getUserCurrency(), $user->getLocale());
        $symbol = $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'];
        $wallet_dto = $this->domainServiceFactory->getWalletService()->getWalletDTO($user);
        $this->userService->resetWonAbove($user);

        return $this->view->setVars([
            'which_form' => 'wallet',
            'form_errors' => $form_errors,
            'bank_account_form' => $bank_account_form,
            'user' => new UserDTO($user),
            'errors' => [],
            'msg' => [],
            'symbol' => $symbol,
            'credit_card_form' => $credit_card_form,
            'show_form_add_fund' => false,
            'wallet' => $wallet_dto,
            'show_box_basic' => true,
            'site_config' => $site_config_dto
        ]);
    }

    public function withDrawAction()
    {
        $user_id = $this->authService->getCurrentUser();
        /** @var User $user */
        $user = $this->userService->getUser($user_id->getId());
        $geoService = $this->domainServiceFactory->getServiceFactory()->getGeoService();
        $countries = $geoService->countryList();
        sort($countries);
        $countries = array_combine(range(1, count($countries)), array_values($countries));
        $bank_account_form = new BankAccountForm($user, ['countries' => $countries] );

        if($this->request->isPost()) {
            if ($bank_account_form->isValid($this->request->getPost()) == false) {
                $messages = $bank_account_form->getMessages(true);
                /**
                 * @var string $field
                 * @var Message\Group $field_messages
                 */
                foreach ($messages as $field => $field_messages) {
                    $errors[] = $field_messages[0]->getMessage();
                    $form_errors[$field] = ' error';
                }
            }else {
                $result = $this->userService->createWithDraw($user, [
                     'bank-name' => $this->request->getPost('bank-name'),
                     'bank-account' => $this->request->getPost('bank-account'),
                     'bank-swift' => $this->request->getPost('bank-swift'),
                     'amount' => $this->request->getPost('funds_value')
                ]);
                if($result->success()){
                    $msg = $result->getValues();
                }else{
                    $errors [] = $result->errorMessage();
                }
            }
        }
    }

    /**
     * @return \Phalcon\Mvc\View
     */
    public function addFundsAction()
    {
        $credit_card_form = new CreditCardForm();
        $credit_card_form = $this->appendElementToAForm($credit_card_form);
        $form_errors = $this->getErrorsArray();
        $funds_value = (int) $this->request->getPost('funds-value');
        $card_number = $this->request->getPost('card-number');
        $card_holder_name = $this->request->getPost('card-holder');
        $expiry_date = $this->request->getPost('expiry-date');
        $cvv = $this->request->getPost('card-cvv');
        $user_id = $this->authService->getCurrentUser();
        /** User $user */
        $user = $this->userService->getUser($user_id->getId());
        $site_config_dto = $this->siteConfigService->getSiteConfigDTO($user->getUserCurrency(), $user->getLocale());
        $wallet_dto = $this->domainServiceFactory->getWalletService()->getWalletDTO($user);
        $errors = [];
        $msg = '';
        $symbol = $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'];
        if($this->request->isPost()) {
            if ($credit_card_form->isValid($this->request->getPost()) == false) {
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
                if(null != $user ){
                    try {
                        $card = new CreditCard(new CardHolderName($card_holder_name), new CardNumber($card_number) , new ExpiryDate($expiry_date), new CVV($cvv));
                        $wallet_service = $this->domainServiceFactory->getWalletService();
                        /** @var ICardPaymentProvider $payXpertCardPaymentStrategy */
                        $payXpertCardPaymentStrategy = $this->di->get('paymentProviderFactory');
                        $currency_euros_to_payment = $this->currencyConversionService->convert(new Money($funds_value * 100, $user->getUserCurrency()), new Currency('EUR'));
                        $credit_card_charge = new CreditCardCharge($currency_euros_to_payment,$this->siteConfigService->getFee(),$this->siteConfigService->getFeeToLimitValue());
                        $result = $wallet_service->rechargeWithCreditCard($payXpertCardPaymentStrategy, $card, $user, $credit_card_charge);
                        if($result->success()) {
                            $converted_net_amount_currency = $this->currencyConversionService->convert($credit_card_charge->getNetAmount(), $user->getUserCurrency());
                            $msg .= 'We added ' . $symbol . ' '  . number_format($converted_net_amount_currency->getAmount() / 100,2,'.',',') . ' to your Wallet Balance';
                            if($credit_card_charge->getIsChargeFee()) {
                                $msg .= ', and charged you an additional '. $site_config_dto->fee .' because it is a transfer below ' . $site_config_dto->feeLimit;
                            }
                            $credit_card_form->clear();
                        } else {
                            $errors[] = 'An error occurred. The response with our payment provider was: ' . $result->returnValues()->errorMessage;
                        }
                    } catch (\Exception $e ) {
                        $errors[] = $e->getMessage();
                        $form_errors['month'] = ' error';
                    }
                }
            }
        }
        $this->view->pick('/account/wallet');
        return $this->view->setVars([
            'form_errors' => $form_errors,
            'errors' => $errors,
            'symbol' => (empty($symbol)) ? $user->getUserCurrency()->getName() : $symbol,
            'credit_card_form' => $credit_card_form,
            'msg' => $msg,
            'site_config' => $site_config_dto,
            'show_form_add_fund' => true,
            'show_winning_copy' => 0,
            'wallet' => $wallet_dto,
            'show_box_basic' => false,
        ]);
    }

    /**
     * @return \Phalcon\Mvc\View
     */
    public function emailAction()
    {
        $userId = $this->authService->getCurrentUser();
        $result = $this->userService->getActiveNotificationsByUser($userId);
        $list_notifications = [];

        if($result->success()) {
            $error_msg = '';
            $notifications_collection = $result->getValues();
            foreach($notifications_collection as $notifications) {
                $list_notifications[] = new UserNotificationsDTO($notifications);
            }
        }else {
            $error_msg = 'An error occurred';
        }
        $this->view->pick('account/email');
        return $this->view->setVars([
            'error' => $error_msg,
            'error_form' => [],
            'list_notifications' => $list_notifications,
            'message' => ''
        ]);
    }

    /**
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface|\Phalcon\Mvc\View
     */
    public function editEmailAction()
    {
        if(!$this->request->isPost()) {
            return $this->response->redirect('/account/email');
        }
        $userId = $this->authService->getCurrentUser();
        /** @var User $user */
        $user = $this->userService->getUser($userId);

        //EMTD we should refactor it this part
        $reach_notification = ($this->request->getPost('jackpot_reach') === 'on');
        $auto_play_funds = ($this->request->getPost('autoplay_funds') === 'on');
        $auto_play_lastdraw = ($this->request->getPost('autoplay_lastdraw') === 'on');
        $results_draw = ($this->request->getPost('results') === 'on');
        $config_value_threshold = $this->request->getPost('config_value_jackpot_reach');
        $config_value_results = $this->request->getPost('config_value_results');

        $message = null;

        $list_notifications = [];
        try {
            if($reach_notification) {
                $notificationType = new NotificationValue(NotificationValue::NOTIFICATION_THRESHOLD, $config_value_threshold);
                $reach_notification = true;
                if(empty($config_value_threshold)) {
                    $reach_notification = false;
                }
                $this->userService->updateEmailNotification($notificationType,$user,$reach_notification);
            } else {
                $notificationType = new NotificationValue(NotificationValue::NOTIFICATION_THRESHOLD,null);
                $this->userService->updateEmailNotification($notificationType,$user,false);
            }
            //Reach notification
            $notificationType = new NotificationValue(NotificationValue::NOTIFICATION_NOT_ENOUGH_FUNDS,'');

            $this->userService->updateEmailNotification($notificationType,$user,$auto_play_funds);
            $notificationType = new NotificationValue(NotificationValue::NOTIFICATION_LAST_DRAW,'');

            $this->userService->updateEmailNotification($notificationType,$user,$auto_play_lastdraw);
            $notificationType = new NotificationValue(NotificationValue::NOTIFICATION_RESULT_DRAW,$config_value_results);

            $this->userService->updateEmailNotification($notificationType,$user,$results_draw);
            $message = 'Your new email settings are saved';
        } catch(\Exception $e) {
            $error[] = $e->getMessage();
        } finally {
            $result = $this->userService->getActiveNotificationsByUser($user);
            if($result->success()) {
                $notifications_collection = $result->getValues();
                foreach($notifications_collection as $notifications) {
                    $list_notifications[] = new UserNotificationsDTO($notifications);
                }
            }else {
                $error[] = 'An error occurred while updated. Please, try it later';
            }
            $errors = $this->validationEmailSettings();
        }

        $this->view->pick('account/email');
        return $this->view->setVars([
            'error_form' => (is_object($errors) && $errors->count() > 0) ? $errors : [],
            'message' => (is_object($errors) && $errors->count() > 0) ? '' : $message,
           // 'errors' => $error,
            'list_notifications' => $list_notifications,
        ]);

    }

    /**
     * @return \Phalcon\Mvc\View
     */
    public function resetPasswordAction()
    {
        $errors = [];
        $form_errors = $this->getErrorsArray();
        $msg = 0;
        $token = $this->request->getPost('token');
        $myaccount_passwordchange_form = new ResetPasswordForm();
        if($this->request->isPost()) {
            if ($myaccount_passwordchange_form->isValid($this->request->getPost()) == false) {
                $messages = $myaccount_passwordchange_form->getMessages(true);
                foreach ($messages as $field => $field_messages) {
                        foreach ( $field_messages as $message ) {
                            $errors[] = $message->getMessage();
                        }
                    $form_errors[$field] = ' error';
                }
            }else {
                $new_password = $this->request->getPost('new-password');
                $user_result = $this->userService->getUserByToken($token);
                if($user_result->success()) {
                    $result = $this->authService->updatePassword($user_result->getValues(), $new_password);
                    if ($result->success()) {
                        //this->response->redirect('/sign-in');
                        $msg = 1;
                    } else {
                        $errors [] = $result->errorMessage();
                    }
                } else {
                    $erros[] = 'Token is not valid';
                }
            }
        }

        $this->view->pick('recovery/index');
        return $this->view->setVars([
            'currency_list' => [],
            'token' => $token,
            'message' => $msg,
            'errors'  => $errors,
            'reset_password_form' => $myaccount_passwordchange_form,
            'form_errors' => $form_errors
        ]);

    }

    /**
     * @param string $userId
     * @return MyAccountForm
     */
    private function getMyACcountForm($userId)
    {
        $geoService = $this->domainServiceFactory->getServiceFactory()->getGeoService();
        $countries = $geoService->countryList();
        sort($countries);
        $countries = array_combine(range(1, count($countries)), array_values($countries));
        $user = $this->userService->getUser($userId);
        $user_dto = new UserDTO($user);
        return new MyAccountForm($user_dto,['countries' => $countries]);
    }

    private function validationEmailSettings()
    {
        $validation = new Validation();
        $messages = [];
        if($this->request->getPost('jackpot_reach') === 'on') {
            $validation->add('config_value_jackpot_reach',
                new Validation\Validator\Numericality([
                   'message' => 'Error. You can insert only a numeric value, symbols and letters are not allowed.'
                ]));
            $messages = $validation->validate($this->request->getPost());
        }
        return $messages;
    }

    /**
     * @return array
     */
    private function getErrorsArray()
    {
        $form_errors = [
            'card-number' => '',
            'card-holder' => '',
            'card-cvv' => '',
            'funds-value' => '',
            'expiry-date' => '',
            'new-password' => '',
            'confirm-password' => '',
            'name' => '',
            'surname' => '',
            'street' => '',
            'zip' => '',
            'city' => '',
            'country' => '',
            'phone_number' => '',
            'bank-name' => '',
            'bank-account' => '',
            'bank-swift' => ''
        ];
        return $form_errors;
    }

    private function appendElementToAForm(Form $form)
    {

        $fund_value = new Text('funds-value', array(
            'autocomplete' => 'off'
        ));
        $fund_value->addValidators(array(
            new PresenceOf(array(
                'message' => 'Insert the amount that you want to add to your funds.'
            )),
            new Numericality(array(
                'message' => 'Insert the amount that you want to add to your funds.'
            )),
            new Regex(array(
                'message' => 'The value in Add funds is not valid. It must be composed of only numbers without decimals or symbols.',
                'pattern' => '/^[\d]{1,8}([\.|\,]\d{1,2})?$/'
            ))
        ));

        $form->add($fund_value);
        return $form;
    }

}