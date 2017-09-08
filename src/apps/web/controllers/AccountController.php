<?php


namespace EuroMillions\web\controllers;


use EuroMillions\web\components\ViewHelper;
use EuroMillions\web\entities\GuestUser;
use EuroMillions\web\entities\User;
use EuroMillions\web\forms\BankAccountForm;
use EuroMillions\web\forms\CreditCardForm;
use EuroMillions\web\forms\MyAccountChangePasswordForm;
use EuroMillions\web\forms\MyAccountForm;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\vo\CreditCardCharge;
use EuroMillions\web\vo\dto\UserDTO;
use EuroMillions\web\vo\dto\UserNotificationsDTO;
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
        if ($user_id instanceof GuestUser) {
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
        if ($this->request->isPost()) {
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
            } else {
                $result = $this->userService->updateUserData([
                    'name' => $this->request->getPost('name'),
                    'surname' => $this->request->getPost('surname'),
                    'street' => $this->request->getPost('street'),
                    'zip' => $this->request->getPost('zip'),
                    'city' => $this->request->getPost('city'),
                    'phone_number' => (int)$this->request->getPost('phone_number'),
                    'country' => $user->getCountry(),
                ], $user->getEmail());
                if ($result->success()) {
                    $msg = $result->getValues();
                } else {
                    $errors [] = $result->errorMessage();
                }
            }
        }
        $this->view->pick('account/index');
        $this->tag->prependTitle('Account Details');
        return $this->view->setVars([
            'form_errors' => $form_errors,
            'which_form' => 'index',
            'errors' => $errors,
            'msg' => $msg,
            'myaccount' => $myaccount_form,
            'password_change' => $myaccount_passwordchange_form
        ]);
    }

    /**
     * @return \Phalcon\Mvc\View
     */
    public function passwordAction()
    {
        $userId = $this->authService->getCurrentUser();
        $myaccount_form = $this->getMyACcountForm($userId);
        $myaccount_passwordchange_form = new MyAccountChangePasswordForm();
        $this->tag->prependTitle('Change Password');
        return $this->view->setVars([
            'form_errors' => [],
            'which_form' => 'password',
            'errors' => [],
            'msg' => null,
            'myaccount' => $myaccount_form,
            'password_change' => $myaccount_passwordchange_form
        ]);
    }

    /**
     * @return \Phalcon\Mvc\View
     */
    public function walletAction()
    {
        $user = $this->authService->getLoggedUser();
        $userDetails['Name'] = (!is_null($user->getName())) ? $user->getName() : '';
        $userDetails['Surname'] = (!is_null($user->getSurname())) ? $user->getSurname() : '';
        $credit_card_form = new CreditCardForm($user, $userDetails);
        $countries = $this->getCountries();
        $credit_card_form = $this->appendElementToAForm($credit_card_form);
        $form_errors = $this->getErrorsArray();
        $bank_account_form = new BankAccountForm($user, ['countries' => $countries]);
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
            'site_config' => $site_config_dto,
            'emerchant_data' => $this->getEmerchantData(),
        ]);
    }

    /**
     * @return \Phalcon\Mvc\View
     */
    public function emailAction()
    {
        $userId = $this->authService->getCurrentUser();
        $result = $this->obtainActiveNotifications($userId);
        $list_notifications = [];

        if ($result->success()) {
            $error_msg = '';
            $notifications_collection = $result->getValues();
            foreach ($notifications_collection as $notifications) {
                $list_notifications[] = new UserNotificationsDTO($notifications);
            }
        } else {
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
     * @return \Phalcon\Mvc\View
     */
    public function editEmailAction()
    {
        if (!$this->request->isPost()) {
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
        $emailMarketing = ($this->request->getPost('email_marketing') === 'on');

        $message = null;

        $list_notifications = [];
        try {
            if ($reach_notification) {
                $notificationType = new NotificationValue(NotificationValue::NOTIFICATION_THRESHOLD, $config_value_threshold);
                $reach_notification = true;
                if (empty($config_value_threshold)) {
                    $reach_notification = false;
                }
                $this->userService->updateEmailNotification($notificationType, $user, $reach_notification);
            } else {
                $notificationType = new NotificationValue(NotificationValue::NOTIFICATION_THRESHOLD, null);
                $this->userService->updateEmailNotification($notificationType, $user, false);
            }
            //Reach notification
            $notificationType = new NotificationValue(NotificationValue::NOTIFICATION_NOT_ENOUGH_FUNDS, '');

            $this->userService->updateEmailNotification($notificationType, $user, $auto_play_funds);
            $notificationType = new NotificationValue(NotificationValue::NOTIFICATION_LAST_DRAW, '');

            $this->userService->updateEmailNotification($notificationType, $user, $auto_play_lastdraw);
            $notificationType = new NotificationValue(NotificationValue::NOTIFICATION_RESULT_DRAW, $config_value_results);

            $this->userService->updateEmailNotification($notificationType, $user, $results_draw);
            $notificationType = new NotificationValue(NotificationValue::NOTIFICATION_EMAIL_MARKETING, '');
            $this->userService->updateEmailNotification($notificationType, $user, $emailMarketing);
            $message = 'email_msgsave_opt';
        } catch (\Exception $e) {
            $error[] = $e->getMessage();
        } finally {
            $result = $this->obtainActiveNotifications($user);
            if ($result->success()) {
                $notifications_collection = $result->getValues();
                foreach ($notifications_collection as $notifications) {
                    $list_notifications[] = new UserNotificationsDTO($notifications);
                }
            } else {
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
    public function depositAction()
    {
        if (!$this->request->isPost()) {
            return $this->response->redirect('/account/wallet');
        }
        $value = $this->request->getPost('funds-value');

        if (!is_numeric($value)) {
            return $this->response->redirect('/account/wallet');
        }
        $currency = $this->userPreferencesService->getCurrency();
        $amount = new Money((int)str_replace('.', '', $value), $currency);
        $amountParsed = $this->currencyConversionService->convert($amount, new Currency('EUR'));
        $creditCardCharged = new CreditCardCharge($amountParsed, $this->siteConfigService->getFee(), $this->siteConfigService->getFeeToLimitValue());
        if ($amountParsed->lessThan(new Money(1200, new Currency('EUR')))) {
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
    protected function getMyACcountForm($userId)
    {
        $countries = $this->getCountries();
        $user = $this->userService->getUser($userId);
        $user_dto = new UserDTO($user);
        return new MyAccountForm($user_dto, ['countries' => $countries]);
    }

    protected function validationEmailSettings()
    {
        $validation = new Validation();
        $messages = [];
        if ($this->request->getPost('jackpot_reach') === 'on') {
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
    protected function getErrorsArray()
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

    protected function appendElementToAForm(Form $form)
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
    protected function getCountries()
    {
        $geoService = $this->domainServiceFactory->getServiceFactory()->getGeoService();
        $countries = $geoService->countryList();
        sort($countries);
        $countries = array_combine(range(1, count($countries)), array_values($countries));
        return $countries;
    }

    /**
     * @param $userId
     * @return \EuroMillions\shared\vo\results\ActionResult
     */
    protected function obtainActiveNotifications($userId)
    {
        $result = $this->userService->getActiveNotificationsByUser($userId);
        return $result;
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
