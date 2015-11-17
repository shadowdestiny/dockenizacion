<?php


namespace EuroMillions\web\controllers;


use EuroMillions\web\entities\User;
use EuroMillions\web\forms\CreditCardForm;
use EuroMillions\web\forms\MyAccountChangePasswordForm;
use EuroMillions\web\forms\MyAccountForm;
use EuroMillions\web\vo\dto\PaymentMethodDTO;
use EuroMillions\web\vo\dto\PlayConfigDTO;
use EuroMillions\web\vo\dto\UserDTO;
use EuroMillions\web\vo\dto\UserNotificationsDTO;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\NotificationType;
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
    /*    $errors = null;
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
        $this->view->pick('account/index');
        return $this->view->setVars([
            'form_errors' => $form_errors,
            'which_form'  => 'password',
            'errors' => $errors,
            'msg' => $msg,
            'myaccount' => $myaccount_form,
            'password_change' => $myaccount_passwordchange_form
        ]);*/

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
        $user = $this->authService->getCurrentUser();
        $payment_methods_dto = [];
        $result_payment_methods = $this->userService->getPaymentMethods($user->getId());
        if($result_payment_methods->success()){
            $payment_methods_list = $result_payment_methods->getValues();
            foreach($payment_methods_list as $payment_method){
                $payment_methods_dto[] = new PaymentMethodDTO($payment_method);
            }
        }
        return $this->view->setVars([
            'which_form' => 'wallet',
            'payment_methods' => $payment_methods_dto
        ]);
    }

    public function editPaymentAction($id)
    {
        if(!empty($id)){
            $payment_method = $this->userService->getPaymentMethod($id);
        }else{
            $payment_method = $this->userService->getPaymentMethod($this->request->getPost('id_payment'));
        }
        $payment_method_dto = new PaymentMethodDTO($payment_method);
        $payment_method_form = new CreditCardForm();
        $errors = null;
        if($this->request->isPost()) {
            if ($payment_method_form->isValid($this->request->getPost()) == false) {
                $messages = $payment_method_form->getMessages(true);
                /**
                 * @var string $field
                 * @var Message\Group $field_messages
                 */
                foreach ($messages as $field => $field_messages) {
                    $errors[] = $field_messages[0]->getMessage();
                    $form_errors[$field] = ' error';
                }
            }else {
                $result_update_payment_method = $this->userService->editMyPaymentMethod($id,[
                    'cardHolderName' => $this->request->getPost('card-holder'),
                    'cardNumber' => $this->request->getPost('card-number'),
                    'month' => $this->request->getPost('month'),
                    'year'  => $this->request->getPost('year'),
                    'cvv'   => $this->request->getPost('card-cvv')
                ]);
                if($result_update_payment_method->success()){
                    $msg = $result_update_payment_method->errorMessage();
                    $payment_method = $this->userService->getPaymentMethod($this->request->getPost('id_payment'));
                    $payment_method_dto = new PaymentMethodDTO($payment_method);
                }else{
                    $errors[] = $result_update_payment_method->errorMessage();
                }
            }

        }
        $this->view->pick('account/wallet');
        return $this->view->setVars([
            'form_errors' => $form_errors,
            'errors' => $errors,
            'msg' => $msg,
            'which_form' => 'edit',
            'payment_methods' => '',
            'payment_method'  => $payment_method_dto,
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
            'list_notifications' => $list_notifications
        ]);
    }

    public function editEmailAction()
    {
        $id = $this->request->getPost('id');
        $active = $this->request->getPost('active');
        $type = $this->request->getPost('type');
        $value = $this->request->getPost('value');
        $notificationType = new NotificationType($type,$value);
        /** @var ActionResult $result */
        $result = $this->userService->updateEmailNotification($notificationType,$id,$active);
        $this->noRender();
        if($result->success()){
            echo json_encode(['result'=> 'OK',
                              'value' => 'Your notifications were updated'
            ]);
        }else{
            echo json_encode(['result'=> 'KO',
                              'value' => 'A problem occurred'
            ]);
        }

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

}