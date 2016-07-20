<?php


namespace EuroMillions\web\controllers;


use EuroMillions\shared\components\widgets\PaginationWidget;
use EuroMillions\web\components\ViewHelper;
use EuroMillions\web\entities\GuestUser;
use EuroMillions\web\entities\User;
use EuroMillions\web\forms\BankAccountForm;
use EuroMillions\web\forms\CreditCardForm;
use EuroMillions\web\forms\MyAccountChangePasswordForm;
use EuroMillions\web\forms\MyAccountForm;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\vo\CardHolderName;
use EuroMillions\web\vo\CardNumber;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\CreditCardCharge;
use EuroMillions\web\vo\CVV;
use EuroMillions\web\vo\dto\PastDrawsCollectionDTO;
use EuroMillions\web\vo\dto\PastDrawsDTO;
use EuroMillions\web\vo\dto\PlayConfigCollectionDTO;
use EuroMillions\web\vo\dto\UpcomingDrawsDTO;
use EuroMillions\web\vo\dto\UserDTO;
use EuroMillions\web\vo\dto\UserNotificationsDTO;
use EuroMillions\web\vo\ExpiryDate;
use EuroMillions\web\vo\NotificationValue;
use Money\Currency;
use Money\Money;
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
        $this->insertGoogleAnalyticsCodeViaEnvironment();
        if($user_id instanceof GuestUser) {
            $this->response->redirect('/sign-in');
            return false;
        } else {
            return true;
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
        $user = $this->authService->getLoggedUser();
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
                ], $user->getEmail());
                if($result->success()){
                    $msg = $result->getValues();
                }else{
                    $errors [] = $result->errorMessage();
                }
            }
        }
        $this->view->pick('account/index');
	$this->tag->prependTitle('Account Details');
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
        $user = $this->authService->getLoggedUser();
        $transactionDtoCollection = $this->transactionService->getTransactionsDTOByUser( $user );

        $page = (!empty($this->request->get('page'))) ? $this->request->get('page') : 1;
        $paginator = $this->getPaginatorAsArray($transactionDtoCollection,10,$page);
        /** @var \Phalcon\Mvc\ViewInterface $paginator_view */
        $paginator_view = (new PaginationWidget($paginator, $this->request->getQuery()))->render();
	
	    $this->tag->prependTitle('Transaction History');
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
	    $this->tag->prependTitle('Change Password');
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
        $user = $this->authService->getLoggedUser();
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrency($this->lotteryService->getNextJackpot('EuroMillions'));
        $myGames = null;
        $playConfigInactivesDTOCollection = [];
        $message_actives = '';
        $message_inactives = '';
        $playConfigDTO = null;

        $myGamesActives = $this->userService->getMyActivePlays($user->getId());
        if($myGamesActives->success()){
            $myGames = $myGamesActives->getValues();
            $playConfigDTO = new UpcomingDrawsDTO($myGames);
            //$playConfigDTO = new PlayConfigCollectionDTO($myGames, $single_bet_price);
        }else{
            $message_actives = $myGamesActives->errorMessage();
        }
        $myGamesInactives = $this->userService->getMyInactivePlays($user->getId());
        if($myGamesInactives->success()){
            $playConfigInactivesDTOCollection = new PastDrawsCollectionDTO($myGamesInactives->getValues());
             //$playConfigInactivesDTOCollection[] = new PlayConfigCollectionDTO($myGamesInactives->getValues(), $single_bet_price);
        }else{
            $message_inactives = $myGamesInactives->errorMessage();
        }

        $page = (!empty($this->request->get('page'))) ? $this->request->get('page') : 1;
        $paginator = $this->getPaginatorAsArray($playConfigInactivesDTOCollection->result['dates'],4,$page);
        /** @var \Phalcon\Mvc\ViewInterface $paginator_view */
        $paginator_view = (new PaginationWidget($paginator, $this->request->getQuery()))->render();
        $this->view->pick('account/games');
	    $this->tag->prependTitle('My Tickets');
        return $this->view->setVars([
            'my_games_actives' => $playConfigDTO,
            'my_games_inactives' => $paginator->getPaginate()->items,
            'jackpot_value' => $jackpot,
            'paginator_view' => $paginator_view,
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
        $countries = $this->getCountries();
        $credit_card_form = $this->appendElementToAForm($credit_card_form);
        $form_errors = $this->getErrorsArray();
        $user = $this->authService->getLoggedUser();
        $bank_account_form = new BankAccountForm($user, ['countries' => $countries] );
        $site_config_dto = $this->siteConfigService->getSiteConfigDTO($user->getUserCurrency(), $user->getLocale());
        $symbol = $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'];
        $wallet_dto = $this->domainServiceFactory->getWalletService()->getWalletDTO($user);
        $ratio = $this->currencyConversionService->getRatio(new Currency('EUR'), $user->getUserCurrency());
        $this->userService->resetWonAbove($user);

        $type = ViewHelper::getNamePaymentType($this->getDI()->get('paymentProviderFactory'));
        $view = $type == 'iframe' ? 'account/wallet_iframe' : 'account/wallet';
	    $this->tag->prependTitle('My Balance');
        $this->view->pick($view);

        return $this->view->setVars([
            'which_form' => 'wallet',
            'form_errors' => $form_errors,
            'bank_account_form' => $bank_account_form,
            'user' => new UserDTO($user),
            'ratio' => $ratio,
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
        $form_errors = $this->getErrorsArray();
        /** @var User $user */
        $user = $this->userService->getUser($user_id->getId());
        $credit_card_form = new CreditCardForm();
        $countries = $this->getCountries();
        $credit_card_form = $this->appendElementToAForm($credit_card_form);
        $bank_account_form = new BankAccountForm($user, ['countries' => $countries] );
        $site_config_dto = $this->siteConfigService->getSiteConfigDTO($user->getUserCurrency(), $user->getLocale());
        $symbol = $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'];
        $ratio = $this->currencyConversionService->getRatio(new Currency('EUR'), $user->getUserCurrency());

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
                     'amount' => $this->request->getPost('amount')
                ]);
                if($result->success()){
                    $user = $this->userService->getUser($user_id->getId());
                    $bank_account_form = new BankAccountForm($user, ['countries' => $countries] );
                    $entity = $bank_account_form->getEntity();
                    $entity->amount = '';
                    $msg = $result->getValues();
                }else{
                    $errors[] = $result->errorMessage();
                }
            }
        }
        $wallet_dto = $this->domainServiceFactory->getWalletService()->getWalletDTO($user);
        $this->view->pick('account/wallet');
	$this->tag->prependTitle('Request a Withdrawal');
        return $this->view->setVars([
            'which_form' => 'withdraw',
            'form_errors' => $form_errors,
            'bank_account_form' => $bank_account_form,
            'user' => new UserDTO($user),
            'errors' => empty($errors) ? [] : $errors,
            'msg' => empty($msg) ? '' : $msg,
            'symbol' => $symbol,
            'ratio' => $ratio,
            'credit_card_form' => $credit_card_form,
            'show_form_add_fund' => false,
            'wallet' => $wallet_dto,
            'show_box_basic' => false,
            'site_config' => $site_config_dto
        ]);

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
        $expiry_date_month = $this->request->getPost('expiry-date-month');
        $expiry_date_year = $this->request->getPost('expiry-date-year');
        $cvv = $this->request->getPost('card-cvv');
        $user_id = $this->authService->getCurrentUser();
        $countries = $this->getCountries();
        /** User $user */
        $user = $this->userService->getUser($user_id->getId());
        $bank_account_form = new BankAccountForm($user, ['countries' => $countries] );
        $site_config_dto = $this->siteConfigService->getSiteConfigDTO($user->getUserCurrency(), $user->getLocale());
        $wallet_dto = $this->domainServiceFactory->getWalletService()->getWalletDTO($user);
        $errors = [];
        $msg = '';
        $symbol = $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'];
        $ratio = $this->currencyConversionService->getRatio(new Currency('EUR'), $user->getUserCurrency());
	    $this->tag->prependTitle('Make a Deposit');

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
                        $card = new CreditCard(new CardHolderName($card_holder_name), new CardNumber($card_number) , new ExpiryDate($expiry_date_month.'/'.'20'.$expiry_date_year), new CVV($cvv));
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
            'which_form' => 'wallet',
            'form_errors' => $form_errors,
            'errors' => $errors,
            'symbol' => (empty($symbol)) ? $user->getUserCurrency()->getName() : $symbol,
            'credit_card_form' => $credit_card_form,
            'msg' => $msg,
            'ratio' => $ratio,
            'bank_account_form' => $bank_account_form,
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
	$this->tag->prependTitle('Email Settings');
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


    public function depositAction()
    {
        if(!$this->request->isPost()) {
            return $this->response->redirect('/account/wallet');
        }
        $value = $this->request->getPost('funds-value');

        if(!is_numeric($value)) {
            return $this->response->redirect('/account/wallet');
        }
        $currency = $this->userPreferencesService->getCurrency();
        $amount = new Money((int)str_replace('.', '', $value), $currency);
        $amountParsed = $this->currencyConversionService->convert($amount, new Currency('EUR'));
        $creditCardCharged = new CreditCardCharge($amountParsed, $this->siteConfigService->getFee(),$this->siteConfigService->getFeeToLimitValue());
        if($amountParsed->lessThan(new Money(1200, new Currency('EUR')))) {
            $this->response->redirect('/account/wallet');
        }
        $this->view->pick('account/deposit');
        $user = $this->authService->getCurrentUser();
        return $this->view->setVars([
            'email' => $user->getEmail()->toNative(),
            'value' => $creditCardCharged->getFinalAmount()->getAmount() / 100
        ]);
    }


    /**
     * @param string $userId
     * @return MyAccountForm
     */
    private function getMyACcountForm($userId)
    {
        $countries = $this->getCountries();
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
            'expiry-date-month' => '',
            'expiry-date-year' => '',
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
            'bank-swift' => '',
            'amount' => ''
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

    /**
     * @return array
     */
    private function getCountries()
    {
        $geoService = $this->domainServiceFactory->getServiceFactory()->getGeoService();
        $countries = $geoService->countryList();
        sort($countries);
        $countries = array_combine(range(1, count($countries)), array_values($countries));
        return $countries;
    }

}
