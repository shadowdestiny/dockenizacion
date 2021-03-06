<?php


namespace EuroMillions\megasena\controllers;


use EuroMillions\shared\helpers\PlayToPay;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\components\ViewHelper;
use EuroMillions\web\controllers\PowerBallPaymentController;
use EuroMillions\web\forms\CreditCardForm;
use EuroMillions\web\vo\CardHolderName;
use EuroMillions\web\vo\CardNumber;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\CVV;
use EuroMillions\web\vo\enum\PaymentSelectorType;
use EuroMillions\web\vo\ExpiryDate;
use EuroMillions\web\vo\PaymentCountry;
use Money\Currency;
use Money\Money;

final class MegaSenaPaymentController extends PowerBallPaymentController
{
    private $lotteryName = "MegaSena";

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
        $isWallet = false;
        $powerball_service = $this->domainServiceFactory->getPowerBallService();
        $playService = $this->domainServiceFactory->getPlayService();
        $errors = [];
        $user_id = $this->authService->getCurrentUser()->getId();
        /** @var User $user */
        $user = $this->userService->getUser($user_id);
        if (null == $user) {
            $this->response->redirect('/' . $this->lottery . '/cart/profile');
            return false;
        }
        $result = $powerball_service->getPlaysFromTemporarilyStorage($user, $this->lotteryName);
        $msg = '';

        $order_view = true;
        //Payment thru wallet ONLY
        if ($this->request->isGet()) {
            $isWallet = true;
            $charge = $this->request->get('charge');
            $user = $this->userService->getUser($user_id);
            if (null != $user && isset($charge)) {
                try {
                    $card = null;
                    $amount = new Money((int)$charge, new Currency('EUR'));
                    $result = $powerball_service->play($user_id, $amount, $card, true, $isWallet, $this->lotteryName);
                    return $this->playResult($result,$result->getValues()->getLottery()->getName());
                } catch (\Exception $e) {
                    $errors[] = $e->getMessage();
                }
            }
        }

        //Payment thru Credit Card or Credit Card + Wallet
        if ($this->request->isPost()) {
            $isWallet = false;
            $order_view = false;
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
            } else {
                /** User $user */
                $user = $this->userService->getUser($user_id);
                if (null != $user) {
                    try {
                        $card = new CreditCard(new CardHolderName($card_holder_name), new CardNumber($card_number), new ExpiryDate($expiry_date_month . '/' . $expiry_date_year), new CVV($cvv));
                        $amount = new Money((int)str_replace('.', '', $funds_value), new Currency('EUR'));
                        $aPaymentProvider = true; //$this->apiFeatureFlagService->getItem('apayment-provider')->getStatus()
                        $result = $this->setPowerBallService($powerball_service)
                            ->setPlayService($playService)
                            ->setPaymentProviderServiceTrait($this->paymentProviderService)
                            ->setPaymentCountryTrait($this->paymentCountry)
                            ->setPaymentSelectorTypeTrait(new PaymentSelectorType(PaymentSelectorType::CREDIT_CARD_METHOD))
                            ->payFromPlay(
                                $user_id,
                                $amount,
                                $card,
                                $payWallet,
                                $isWallet,
                                'MegaSena',
                                $aPaymentProvider
                            );

                        if(!empty($result)) {
                            return $this->playResult($result, 'megasena');
                        }
                        return false; //When we use the "PaymentRedirectContext" we force to return false.
                    } catch (\Exception $e) {
                        $errors[] = $e->getMessage();
                    }
                }
            }
        }

        $type = ViewHelper::getNamePaymentType($this->getDI()->get('paymentProviderFactory'));
        $view = $type == 'iframe' ? 'cart/order_iframe' : 'cart/order';
        $this->view->pick($view);
        return $this->dataOrderView($user, $result, $form_errors, $msg, $credit_card_form, $errors, $order_view);
    }


    /**
     * @param ActionResult $result
     * @return bool
     */
    protected function playResult(ActionResult $result, $lotteryName='powerball')
    {
        if ($result->success()) {
            $this->response->redirect('/' . mb_strtolower($lotteryName) . '/result/success/'.mb_strtolower($lotteryName));
            return false;
        } else {
            $this->response->redirect('/euromillions/result/failure');
            return false;
        }
    }

}