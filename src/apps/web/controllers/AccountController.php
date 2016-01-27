<?php


namespace EuroMillions\web\controllers;


use apps\web\forms\MyAccountWalletAddFunds;
use EuroMillions\web\entities\User;
use EuroMillions\web\forms\CreditCardForm;
use EuroMillions\web\forms\MyAccountChangePasswordForm;
use EuroMillions\web\forms\MyAccountForm;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\services\card_payment_providers\FakeCardPaymentProvider;
use EuroMillions\web\services\card_payment_providers\payxpert\PayXpertConfig;
use EuroMillions\web\services\card_payment_providers\PayXpertCardPaymentProvider;
use EuroMillions\web\services\WalletService;
use EuroMillions\web\vo\CardHolderName;
use EuroMillions\web\vo\CardNumber;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\CVV;
use EuroMillions\web\vo\dto\PlayConfigDTO;
use EuroMillions\web\vo\dto\UserDTO;
use EuroMillions\web\vo\dto\UserNotificationsDTO;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\ExpiryDate;
use EuroMillions\web\vo\NotificationType;
use EuroMillions\web\vo\UserId;
use Money\Money;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation;
use Phalcon\Validation\Message;

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
            if ($myaccount_passwordchange_form->isValid($this->request->getPost()) == false) {
                $messages = $myaccount_passwordchange_form->getMessages(true);
                foreach ($messages as $field => $field_messages) {
                    $errors[] = $field_messages[0]->getMessage();
                    $form_errors[$field] = ' error';
                }
            }else {
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
            'form_errors' => !empty($form_errors) ? $form_errors : [],
            'which_form'  => 'password',
            'errors' => $errors,
            'msg' => !empty($msg) ? $msg : '',
            'myaccount' => $myaccount_form,
            'password_change' => $myaccount_passwordchange_form
        ]);

    }

    public function gamesAction()
    {
        $user = $this->authService->getCurrentUser();
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrency($this->lotteriesDataService->getNextJackpot('EuroMillions'));
        $myGames = null;
        $playConfigActivesDTOCollection = [];
        $playConfigInactivesDTOCollection = [];
        $message_actives = '';
        $message_inactives = '';

        if(!empty($user)){
            $myGamesActives = $this->userService->getMyActivePlays($user->getId());
            if($myGamesActives->success()){
                foreach($myGamesActives->getValues() as $game){
                    $playConfigActivesDTOCollection[] = new PlayConfigDTO($game);
                }
            }else{
                $message_actives = $myGamesActives->errorMessage();
            }
            $myGamesInactives = $this->userService->getMyInactivePlays($user->getId());
            if($myGamesInactives->success()){
                foreach($myGamesInactives->getValues() as $myGamesInactives){
                    $playConfigInactivesDTOCollection[] = new PlayConfigDTO($myGamesInactives);
                }
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
        $form_errors = $this->getErrorsArray();

        return $this->view->setVars([
            'which_form' => 'wallet',
            'form_errors' => $form_errors,
            'errors' => [],
            'msg' => [],
            'credit_card_form' => $credit_card_form,
            'show_form_add_fund' => false,
            'show_box_basic' => true
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
        $year = $this->request->getPost('year');
        $month = $this->request->getPost('month');
        $cvv = $this->request->getPost('card-cvv');

        if($this->request->isPost()) {
            if ($credit_card_form->isValid($this->request->getPost()) == false) {
                $messages = $credit_card_form->getMessages(true);
                /**
                 * @var string $field
                 * @var Message\Group $field_messages
                 */
                foreach ($messages as $field => $field_messages) {
                    $errors[] = $field_messages[0]->getMessage();
                    $form_errors[$field] = ' error';
                }
            }else {
                $user_id = $this->authService->getCurrentUser();
                /** User $user */
                $user = $this->userService->getUser($user_id->getId());
                if(null != $user ){
                    $config_payment = $this->di->get('config')['payxpert'];
                    $card = new CreditCard(new CardHolderName($card_holder_name), new CardNumber($card_number) , new ExpiryDate($month.'/'.$year), new CVV($cvv));
                    $payXpertConfig = new PayXpertConfig($config_payment['originator_id'], $config_payment['originator_name'], $config_payment['api_key']);
                    $wallet_service = $this->domainServiceFactory->getWalletService();
                    $result = $wallet_service->rechargeWithCreditCard(new PayXpertCardPaymentProvider($payXpertConfig), $card, $user, new Money($funds_value, $user->getUserCurrency()));
                    if($result->success()) {
                        $msg = 'Your balance was update. Your current balance is: ' . $user->getBalance() / 100;
                    } else {
                        $errors[] = 'An error occurred. The response with our payment provider was: ' . $result->returnValues()->errorMessage;
                    }
                }
            }
        }
        $this->view->pick('/account/wallet');
        return $this->view->setVars([
            'form_errors' => $form_errors,
            'errors' => $errors,
            'credit_card_form' => $credit_card_form,
            'msg' => !empty($msg) ? $msg : '',
            'show_form_add_fund' => true,
            'show_box_basic' => false,
        ]);

    }

    public function emailAction()
    {
        $userId = $this->authService->getCurrentUser();
        $result = $this->userService->getActiveNotificationsByUser($userId);
        $list_notifications = null;

        if($result->success()) {
            $notifications_collection = $result->getValues();
            foreach($notifications_collection as $notifications) {
                $list_notifications[] = new UserNotificationsDTO($notifications);
            }
        }else {
            $error_msg = 'An error occurred';
        }
        $this->view->pick('account/email');
        return $this->view->setVars([
            'error' => (!empty($error_msg)) ? $error_msg : '',
            'error_form' => [],
            'list_notifications' => $list_notifications,
            'message' => ''
        ]);
    }

    public function editEmailAction()
    {
        if(!$this->request->isPost()) {
            return $this->response->redirect('account/email');
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

        $list_notifications = null;
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

    private function getMyACcountForm($userId)
    {
        $geoService = $this->domainServiceFactory->getServiceFactory()->getGeoService();
        $countries = $geoService->countryList();
        sort($countries);
        $countries = array_combine(range(1, count($countries)), array_values($countries));
        $user = $this->userService->getUser($userId->getId());
        $user_dto = new UserDTO($user);
        $myaccount_form = new MyAccountForm($user_dto,['countries' => $countries]);
        return $myaccount_form;
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
        ];
        return $form_errors;
    }

    private function appendElementToAForm(Form $form)
    {

        $fund_value = new Text('funds-value', array(
            'placeholder' => 'Insert an ammount'
        ));
        $fund_value->addValidators(array(
            new PresenceOf(array(
                'message' => 'A value is required to add funds'
            ))
        ));
       $form->add($fund_value);
        return $form;
    }
}