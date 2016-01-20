<?php


namespace EuroMillions\web\controllers;


use EuroMillions\web\entities\User;
use EuroMillions\web\forms\MyAccountChangePasswordForm;
use EuroMillions\web\forms\MyAccountForm;
use EuroMillions\web\vo\ActionResult;
use EuroMillions\web\vo\dto\PlayConfigDTO;
use EuroMillions\web\vo\dto\UserDTO;
use EuroMillions\web\vo\dto\UserNotificationsDTO;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\NotificationType;
use Phalcon\Mvc\Model\Validator\Numericality;
use Phalcon\Validation;
use Phalcon\Validation\Message;

class AccountController extends PublicSiteControllerBase
{

    public function TransactionAction(){}

    public function indexAction()
    {
        $errors = null;
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
        $errors = null;
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
            $myGamesActives = $this->userService->getMyPlaysActives($user->getId());
            if($myGamesActives->success()){
                foreach($myGamesActives->getValues() as $game){
                    $playConfigActivesDTOCollection[] = new PlayConfigDTO($game);
                }
            }else{
                $message_actives = $myGamesActives->errorMessage();
            }
            $myGamesInactives = $this->userService->getMyPlaysInactives($user->getId());
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
        return $this->view->setVars([
            'which_form' => 'wallet',
        ]);
    }

    public function addFundsAction()
    {

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
                   'message' => 'Error. You should insert a numeric value.'

                ]));
            $messages = $validation->validate($this->request->getPost());
        }
        return $messages;
    }

}