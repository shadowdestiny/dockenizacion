<?php


namespace EuroMillions\web\controllers;


use EuroMillions\web\entities\User;
use EuroMillions\web\forms\CreditCardForm;

class OrderController extends CartController
{


    public function orderAction()
    {
        $user_id = $this->request->get('user');
        $current_user_id = $this->authService->getCurrentUser()->getId();
        $credit_card_form = new CreditCardForm();
        $form_errors = $this->getErrorsArray();
        $play_service = $this->domainServiceFactory->getPlayService();
        $msg = '';
        $errors = [];
        if(!empty($user_id)) {
            $result = $play_service->getPlaysFromGuestUserAndSwitchUser($user_id,$current_user_id);
            $user = $this->userService->getUser($current_user_id);
        } else {
            /** @var User $user */
            $user = $this->userService->getUser($current_user_id);
            if(!$user) {
                $this->response->redirect('/'.$this->lottery.'/play');
                return false;
            }
            $result = $play_service->getPlaysFromTemporarilyStorage($user);
        }
        if(!$result->success()) {
            $this->response->redirect('/'.$this->lottery.'/play');
            return false;
        }
        $this->view->pick('cart/order');
        return $this->dataOrderView($user, $result, $form_errors, $msg, $credit_card_form, $errors);
    }


}