<?php


namespace EuroMillions\web\controllers;


use EuroMillions\web\forms\MyAccountChangePasswordForm;
use EuroMillions\web\forms\MyAccountForm;
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
            'password_change' => $myaccount_passwordchange_form
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


}