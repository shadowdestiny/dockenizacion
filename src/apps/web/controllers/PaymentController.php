<?php


namespace EuroMillions\web\controllers;


use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\entities\User;
use EuroMillions\web\forms\CreditCardForm;
use EuroMillions\web\vo\CardHolderName;
use EuroMillions\web\vo\CardNumber;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\CVV;
use EuroMillions\web\vo\ExpiryDate;
use Money\Currency;
use Money\Money;

class PaymentController extends CartController
{



    public function paymentAction()
    {
        $credit_card_form = new CreditCardForm();
        $form_errors = $this->getErrorsArray();
        $funds_value = $this->request->getPost('funds');
        $card_number = $this->request->getPost('card-number');
        $card_holder_name = $this->request->getPost('card-holder');
        $expiry_date_month = $this->request->getPost('expiry-date-month');
        $expiry_date_year = $this->request->getPost('expiry-date-year') + 2000; //This is Antonio, the first developer at EuroMillions. If something failed horribly and you are looking at this, I'm glad that my code survived for more than 75 years, but also my apologies. We changed from 4 digits years to 2 digits, and the implications in all the validators where so much, and we need to finish to go to production, that I decided to include this ugly hack. Luckily, it will work until after I'm dead and rotten.
        $cvv = $this->request->getPost('card-cvv');
        $payWallet = $this->request->getPost('paywallet') !== 'false';
        $play_service = $this->domainServiceFactory->getPlayService();
        $errors = [];
        $user_id = $this->authService->getCurrentUser()->getId();
        /** @var User $user */
        $user = $this->userService->getUser($user_id);
        if( null == $user ) {
            $this->response->redirect('/'.$this->lottery.'/cart/profile');
            return false;
        }
        $result = $play_service->getPlaysFromTemporarilyStorage($user);
        $msg = '';


        $order_view = true;
        if($this->request->isGet()) {
            $charge = $this->request->get('charge');
            $user = $this->userService->getUser($user_id);
            if( null != $user && isset($charge) ){
                try {
                    $card = null;
                    $amount = new Money((int) $charge, new Currency('EUR'));
                    $result = $play_service->play($user_id,$amount, $card,true);
                    return $this->playResult($result);
                } catch (\Exception $e ) {
                    $errors[] = $e->getMessage();
                }
            }
        }

        if($this->request->isPost()) {
            $order_view = false;
            if ($credit_card_form->isValid($this->request->getPost()) == false ) {
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
                /** User $user */
                $user = $this->userService->getUser($user_id);
                if(null != $user ){
                    try {
                        $card = new CreditCard(new CardHolderName($card_holder_name), new CardNumber($card_number) , new ExpiryDate($expiry_date_month.'/'.$expiry_date_year), new CVV($cvv));
                        $amount = new Money((int) str_replace('.','',$funds_value), new Currency('EUR'));
                        $result = $play_service->play($user_id,$amount, $card,$payWallet);
                        return $this->playResult($result);
                    } catch (\Exception $e ) {
                        $errors[] = $e->getMessage();
                    }
                }
            }
        }

        $this->view->pick('/cart/order');
        return $this->dataOrderView($user, $result, $form_errors, $msg, $credit_card_form, $errors, $order_view);
    }


    /**
     * @param ActionResult $result
     * @return bool
     */
    protected function playResult(ActionResult $result)
    {
        if($result->success()) {
            $this->response->redirect('/'.$this->lottery.'/result/success');
            return false;
        } else {
            $this->response->redirect('/'.$this->lottery.'/result/failure');
            return false;
        }
    }


}