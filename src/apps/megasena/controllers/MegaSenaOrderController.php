<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 6/11/18
 * Time: 9:50
 */

namespace EuroMillions\megasena\controllers;

use EuroMillions\shared\controllers\CartController;
use EuroMillions\web\components\ViewHelper;
use EuroMillions\web\forms\CreditCardForm;

class MegaSenaOrderController extends CartController
{

    public function orderAction()
    {
        $user_id = $this->request->get('user');
        $current_user_id = $this->authService->getCurrentUser()->getId();
        $credit_card_form = new CreditCardForm();
        $form_errors = $this->getErrorsArray();
        $play_service = $this->domainServiceFactory->getPowerBallService();
        $msg = '';
        $errors = [];
        if(!empty($user_id)) {
            $result = $play_service->getPlaysFromGuestUserAndSwitchUser($user_id,$current_user_id,$this->lottery);
            $user = $this->userService->getUser($current_user_id);
        } else {
            /** @var User $user */
            $user = $this->userService->getUser($current_user_id);
            if(!$user) {
                $this->response->redirect('/'.$this->lottery.'/play');
                return false;
            }

            $result = $play_service->getPlaysFromTemporarilyStorage($user, $this->lottery);
        }
        if(!$result->success()) {
            $this->response->redirect('/'.$this->lottery.'/play');
            return false;
        }

        $type = ViewHelper::getNamePaymentType($this->getDI()->get('paymentProviderFactory'));

        $view = $type == 'iframe' ? 'cart/order_iframe' : 'cart/order';
        $this->view->pick($view);
        return $this->dataOrderView($user, $result, $form_errors, $msg, $credit_card_form, $errors);
    }


}