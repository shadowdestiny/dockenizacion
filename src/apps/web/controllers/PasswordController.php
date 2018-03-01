<?php


namespace EuroMillions\web\controllers;


use EuroMillions\web\forms\MyAccountChangePasswordForm;
use EuroMillions\web\forms\MyAccountForm;
use EuroMillions\web\forms\ResetPasswordForm;
use EuroMillions\web\vo\dto\UserDTO;
use Phalcon\Validation\Message;

class PasswordController extends PublicSiteControllerBase
{


    /**
     * @return \Phalcon\Mvc\View
     */
    public function resetAction()
    {
        $errors = [];
        $msg = null;
        $form_errors = [];
        $userId = $this->authService->getCurrentUser();
        $user = $this->userService->getUser($userId);
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
                if(null != $user) {
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
        }

        $pickView = $this->authService->isLogged() ? 'account/password' : 'user-access/updatePassword';
        $this->view->pick($pickView);
        return $this->view->setVars([
            'form_errors' => $form_errors,
            'which_form'  => 'password',
            'errors' => $errors,
            'msg' => $msg,
            'myaccount' => $myaccount_form,
            'password_change' => $myaccount_passwordchange_form,
            'message' => '',
            'error_form' => [],
        ]);
    }

    private function getMyACcountForm($userId)
    {
        $geoService = $this->domainServiceFactory->getServiceFactory()->getGeoService();
        $countries = $geoService->countryList();
        sort($countries);
        $countries = array_combine(range(1, count($countries)), array_values($countries));
        $user = $this->userService->getUser($userId);
        $user_dto = $user ? new UserDTO($user) : null;
        return new MyAccountForm($user_dto,['countries' => $countries]);
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
     * @return array
     */
    private function getErrorsArray()
    {
        $form_errors = [
            'new-password' => '',
            'confirm-password' => '',
        ];
        return $form_errors;
    }


}