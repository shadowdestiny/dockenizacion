<?php


namespace EuroMillions\web\controllers;


use EuroMillions\web\entities\User;
use EuroMillions\web\forms\CreditCardForm;
use EuroMillions\web\forms\MyAccountChangePasswordForm;
use EuroMillions\web\forms\MyAccountForm;
use EuroMillions\web\forms\ResetPasswordForm;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\vo\CardHolderName;
use EuroMillions\web\vo\CardNumber;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\CreditCardCharge;
use EuroMillions\web\vo\CVV;
use EuroMillions\web\vo\dto\PlayConfigDTO;
use EuroMillions\web\vo\dto\UserDTO;
use EuroMillions\web\vo\dto\UserNotificationsDTO;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\ExpiryDate;
use EuroMillions\web\vo\NotificationType;
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

    public function TransactionAction(){}

    public function indexAction()
    {
        $errors = [];
        $form_errors = [];
        $msg = '';
        $userId = $this->authService->getCurrentUser();
        $myaccount_form = $this->getMyACcountForm($userId);
        $myaccount_passwordchange_form = new MyAccountChangePasswordForm();
        //$form_errors = $this->getErrorsArray();
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
                    'email'    => new Email($this->request->getPost('email')),
                    'country'  => $this->request->getPost('country'),
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

    public function passwordAction()
    {
        $errors = [];
        $form_errors = [];
        $msg = '';
        $userId = $this->authService->getCurrentUser();
        $user = $this->userService->getUser($userId->getId());
        $myaccount_form = $this->getMyACcountForm($userId);
        $myaccount_passwordchange_form = new MyAccountChangePasswordForm();
        if($this->request->isPost()) {
            if ($myaccount_passwordchange_form->isValid($this->request->getPost()) === false) {
                $messages = $myaccount_passwordchange_form->getMessages(true);
                /**
                 * @var  $field
                 * @var Message[] $field_messages
                 */
                foreach ($messages as $field => $field_messages) {
                    $errors[] = $field_messages[0]->getMessage();
                    $form_errors[$field] = ' error';
                }
            } else {
                $result_same_password = $this->authService->samePassword($user,$this->request->getPost('old-password'));
                if($result_same_password->success()) {
                    $result = $this->authService->updatePassword($user, $this->request->getPost('new-password'));
                    if ($result->success()) {
                        $msg = $result->getValues();
                    } else {
                        $errors [] = $result->errorMessage();
                    }
                }else{
                    $errors[] = $result_same_password->errorMessage();
                }
            }
        }
        //$this->view->pick('account/index');
        return $this->view->setVars([
            'form_errors' => $form_errors,
            'which_form'  => 'password',
            'errors' => $errors,
            'msg' => $msg,
            'myaccount' => $myaccount_form,
            'password_change' => $myaccount_passwordchange_form
        ]);

    }

    public function gamesAction()
    {
        $user = $this->authService->getCurrentUser();
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrency($this->lotteriesDataService->getNextJackpot('EuroMillions'));
        $single_bet_price = $this->domainServiceFactory->getLotteriesDataService()->getSingleBetPriceByLottery('EuroMillions');
        $myGames = null;
        $playConfigActivesDTOCollection = [];
        $playConfigInactivesDTOCollection = [];
        $message_actives = '';
        $message_inactives = '';

        if(!empty($user)){
            $myGamesActives = $this->userService->getMyActivePlays($user->getId());
            if($myGamesActives->success()){
                $playConfigActivesDTOCollection[] = new PlayConfigDTO($myGamesActives->getValues(), $single_bet_price);
            }else{
                $message_actives = $myGamesActives->errorMessage();
            }
            $myGamesInactives = $this->userService->getMyInactivePlays($user->getId());
            if($myGamesInactives->success()){
                $playConfigInactivesDTOCollection[] = new PlayConfigDTO($myGamesInactives->getValues(), $single_bet_price);
            }else{
                $message_inactives = $myGamesInactives->errorMessage();
            }
        }
        $this->view->pick('account/games');
        return $this->view->setVars([
            'my_games_actives' => $playConfigActivesDTOCollection,
            'my_games_inactives' => $playConfigInactivesDTOCollection,
            'jackpot_value' => $jackpot->getAmount()/100,
            'message_actives' => $message_actives,
            'message_inactives' => $message_inactives
        ]);
    }

    public function walletAction()
    {
        $credit_card_form = new CreditCardForm();
        $credit_card_form = $this->appendElementToAForm($credit_card_form);
        $locale = $this->request->getBestLanguage();
        $form_errors = $this->getErrorsArray();
        $user_id = $this->authService->getCurrentUser();
        /** @var User $user */
        $user = $this->userService->getUser($user_id->getId());
        $fee_value_with_currency = $this->siteConfigService->getFeeFormatMoney($user->getUserCurrency(),$locale);
        $fee_to_limit_value_with_currency = $this->siteConfigService->getFeeLimitFormatMoney($user->getUserCurrency(), $locale);
        $fee_to_limit_value = $this->siteConfigService->getFeeToLimitValue()->getAmount() / 1000;
        $symbol = $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'];

        return $this->view->setVars([
            'which_form' => 'wallet',
            'form_errors' => $form_errors,
            'errors' => [],
            'msg' => [],
            'symbol' => $symbol,
            'credit_card_form' => $credit_card_form,
            'show_form_add_fund' => false,
            'show_box_basic' => true,
            'fee_to_limit_value' => $fee_to_limit_value,
            'fee' => $fee_value_with_currency,
            'fee_to_limit' => $fee_to_limit_value_with_currency
        ]);
    }

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
        $errors = [];
        $msg = '';

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
                        $symbol = $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'];
                        $card = new CreditCard(new CardHolderName($card_holder_name), new CardNumber($card_number) , new ExpiryDate($expiry_date), new CVV($cvv));
                        $wallet_service = $this->domainServiceFactory->getWalletService();
                        /** @var ICardPaymentProvider $payXpertCardPaymentStrategy */
                        $payXpertCardPaymentStrategy = $this->di->get('paymentProviderFactory');
                        $currency_euros_to_payment = $this->currencyService->convert(new Money($funds_value * 100, $user->getUserCurrency()), new Currency('EUR'));
                        $fee_value = $this->siteConfigService->getFee();
                        $fee_to_limit_value = $this->siteConfigService->getFeeToLimitValue();
                        $credit_card_charge = new CreditCardCharge($currency_euros_to_payment,$fee_value,$fee_to_limit_value);
                        $result = $wallet_service->rechargeWithCreditCard($payXpertCardPaymentStrategy, $card, $user, $credit_card_charge);
                        if($result->success()) {
                            $converted_net_amount_currency = $this->currencyService->convert($credit_card_charge->getNetAmount(), $user->getUserCurrency());
                            $converted_fee_value_currency = $this->currencyService->convert($fee_value, $user->getUserCurrency());
                            $converted_feelimit_value_currency = $this->currencyService->convert($fee_to_limit_value, $user->getUserCurrency());
                            $msg .= 'We added ' . $symbol . ' '  . number_format($converted_net_amount_currency->getAmount() / 100,2,'.',',');
                            if($credit_card_charge->getIsChargeFee()) {
                                $msg .= ', and charged you an additional '. $symbol . ' ' . number_format($converted_fee_value_currency->getAmount() / 100,2,'.',',') .' because it is a transfer below ' . $symbol . ' ' . number_format($converted_feelimit_value_currency->getAmount() / 1000,2,'.',',');
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

        $locale = $this->request->getBestLanguage();
        $fee_value_with_currency = $this->siteConfigService->getFeeFormatMoney($user->getUserCurrency(), $locale);
        $fee_to_limit_value_with_currency = $this->siteConfigService->getFeeLimitFormatMoney($user->getUserCurrency(), $locale);
        $fee_to_limit_value = $this->siteConfigService->getFeeToLimitValue()->getAmount() / 1000;

        $this->view->pick('/account/wallet');
        return $this->view->setVars([
            'form_errors' => $form_errors,
            'errors' => $errors,
            'symbol' => (empty($symbol)) ? $user->getUserCurrency()->getName() : $symbol,
            'credit_card_form' => $credit_card_form,
            'msg' => $msg,
            'fee_to_limit_value' => $fee_to_limit_value,
            'fee' => $fee_value_with_currency,
            'fee_to_limit' => $fee_to_limit_value_with_currency,
            'show_form_add_fund' => true,
            'show_box_basic' => false,
        ]);
    }

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

    public function editEmailAction()
    {
        if(!$this->request->isPost()) {
            return $this->response->redirect('/account/email');
        }
        $userId = $this->authService->getCurrentUser();
        /** @var User $user */
        $user = $this->userService->getUser($userId->getId());

        //EMTD we should refactor it this part
        $reach_notification = ($this->request->getPost('jackpot_reach') == 'on') ? true : false;
        $auto_play_funds = ($this->request->getPost('autoplay_funds') == 'on') ? true: false;
        $auto_play_lastdraw = ($this->request->getPost('autoplay_lastdraw') == 'on') ? true: false;
        $results_draw = ($this->request->getPost('results') == 'on') ? true : false;
        $config_value_threshold = $this->request->getPost('config_value_jackpot_reach');
        $config_value_results = $this->request->getPost('config_value_results');

        $message = null;

        $list_notifications = [];
        try {
            if($reach_notification) {
                $notificationType = new NotificationType(NotificationType::NOTIFICATION_THRESHOLD, $config_value_threshold);
                $reach_notification = true;
                if(empty($config_value_threshold)) {
                    $reach_notification = false;
                }
                $this->userService->updateEmailNotification($notificationType,$user,$reach_notification);
            } else {
                $notificationType = new NotificationType(NotificationType::NOTIFICATION_THRESHOLD,null);
                $this->userService->updateEmailNotification($notificationType,$user,false);
            }
            //Reach notification
            $notificationType = new NotificationType(NotificationType::NOTIFICATION_NOT_ENOUGH_FUNDS,'');

            $this->userService->updateEmailNotification($notificationType,$user,$auto_play_funds);
            $notificationType = new NotificationType(NotificationType::NOTIFICATION_LAST_DRAW,'');

            $this->userService->updateEmailNotification($notificationType,$user,$auto_play_lastdraw);
            $notificationType = new NotificationType(NotificationType::NOTIFICATION_RESULT_DRAW,$config_value_results);

            $this->userService->updateEmailNotification($notificationType,$user,$results_draw);
            $message = 'Your new email settings are saved';
        } catch(\Exception $e) {
            $error[] = $e->getMessage();
        } finally {
            $result = $this->userService->getActiveNotificationsByUser($userId);
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

    public function resetPasswordAction()
    {
        $errors = [];
        $form_errors = $this->getErrorsArray();
        $msg = false;
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
                        $msg = true;
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

    private function getMyACcountForm($userId)
    {
        $geoService = $this->domainServiceFactory->getServiceFactory()->getGeoService();
        $countries = $geoService->countryList();
        sort($countries);
        $countries = array_combine(range(1, count($countries)), array_values($countries));
        $user = $this->userService->getUser($userId->getId());
        $user_dto = new UserDTO($user);
        return new MyAccountForm($user_dto,['countries' => $countries]);
    }

    private function validationEmailSettings()
    {
        $validation = new Validation();
        $messages = [];
        if($this->request->getPost('jackpot_reach') == 'on') {
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
            'confirm-password' => ''
        ];
        return $form_errors;
    }

    private function appendElementToAForm(Form $form)
    {

        $fund_value = new Text('funds-value', array(
            'placeholder' => 'Insert an amount',
            'autocomplete' => 'off'
        ));
        $fund_value->addValidators(array(
            new PresenceOf(array(
                'message' => 'Insert the amount that you want to add to your funds xxx.'
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