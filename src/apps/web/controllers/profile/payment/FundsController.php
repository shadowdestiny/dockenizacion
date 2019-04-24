<?php


namespace EuroMillions\web\controllers\profile\payment;

use EuroMillions\shared\enums\PaymentProviderEnum;
use EuroMillions\shared\helpers\SiteHelpers;
use EuroMillions\web\controllers\AccountController;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\forms\BankAccountForm;
use EuroMillions\web\forms\CreditCardForm;
use EuroMillions\web\services\card_payment_providers\shared\PaymentRedirectContext;
use EuroMillions\web\services\factories\OrderFactory;
use EuroMillions\web\vo\CardHolderName;
use EuroMillions\web\vo\CardNumber;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\CreditCardCharge;
use EuroMillions\web\vo\CVV;
use EuroMillions\web\vo\Discount;
use EuroMillions\web\vo\dto\ChasierDTO;
use EuroMillions\web\vo\dto\UserDTO;
use EuroMillions\web\vo\enum\PaymentSelectorType;
use EuroMillions\web\vo\ExpiryDate;
use Money\Currency;
use Money\Money;

class FundsController extends AccountController
{
    const PAYMENT_SELECTOR_TYPE = PaymentSelectorType::CREDIT_CARD_METHOD;  //TODO: remove when the selector on the frontend is done.

    /**
     * @return \Phalcon\Mvc\View
     */
    public function addFundsAction()
    {
        $credit_card_form = new CreditCardForm();
        $credit_card_form = $this->appendElementToAForm($credit_card_form);
        $form_errors = $this->getErrorsArray();
        $funds_value = (int) $this->request->getPost('funds-value');
        $card_number = $this->request->getPost('card-number');
        $card_holder_name = $this->request->getPost('card-holder');
        $expiry_date_month = $this->request->getPost('expiry-date-month');
        $expiry_date_year = $this->request->getPost('expiry-date-year');
        $cvv = $this->request->getPost('card-cvv');
        $user_id = $this->authService->getCurrentUser();
        $countries = $this->getCountries();
        /** User $user */
        $user = $this->userService->getUser($user_id->getId());
        $bank_account_form = new BankAccountForm($user, ['countries' => $countries]);
        $site_config_dto = $this->siteConfigService->getSiteConfigDTO($user->getUserCurrency(), $user->getLocale());
        $wallet_dto = $this->domainServiceFactory->getWalletService()->getWalletDTO($user);
        $errors = [];
        $msg = '';
        $symbol = $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'];
        $ratio = $this->currencyConversionService->getRatio(new Currency('EUR'), $user->getUserCurrency());
        $this->tag->prependTitle('Make a Deposit');

        if ($this->request->isPost() && $this->authService->getLoggedUser()->getValidated()) {
            if ($credit_card_form->isValid($this->request->getPost()) == false) {
                $messages = $credit_card_form->getMessages(true);
                /**
                 * @var string $field
                 * @var Message\Group $field_messages
                 */
                //check date
                foreach ($messages as $field => $field_messages) {
                    $this->flash->error($field_messages[0]->getMessage());
                    $form_errors[$field] = ' error';
                }
            } else {
                if (null != $user) {
                    try {
                        $card = new CreditCard(new CardHolderName($card_holder_name), new CardNumber($card_number), new ExpiryDate($expiry_date_month.'/'.'20'.$expiry_date_year), new CVV($cvv));
                        $wallet_service = $this->domainServiceFactory->getWalletService();

                        $currency_euros_to_payment = $this->currencyConversionService->convert(new Money($funds_value * 100, $user->getUserCurrency()), new Currency('EUR'));
                        $credit_card_charge = new CreditCardCharge($currency_euros_to_payment, $this->siteConfigService->getFee(), $this->siteConfigService->getFeeToLimitValue());

                        $money = new Money(0, new Currency('EUR'));
                        $amount = $credit_card_charge->getFinalAmount();

                        $user_id = $this->authService->getCurrentUser()->getId();
                        $user = $this->userService->getUser($user_id);

                        $playconfig = new PlayConfig();
                        $playconfig->setFrequency(1);
                        $playconfig->setUser($user);

                        $depositLottery = new Lottery();
                        $depositLottery->initialize([
                            'id' => 1,
                            'name' => 'Deposit',
                            'active' => 1,
                            'frequency' => 'freq',
                            'draw_time' => 'draw',
                            'single_bet_price' => new Money(23500, new Currency('EUR')),
                        ]);
                        $order = OrderFactory::create([$playconfig], $amount, $money, $money, new Discount(0, []), $depositLottery, 'Deposit', false);

                        $onlyPay = true;
                        $apaymentProvider = true; //$this->domainServiceFactory->getServiceFactory()->getFeatureFlagApiService()->getItem("apayment-provider")->getStatus();

                        $cardPaymentProvider = $this->paymentProviderService->createCollectionFromTypeAndCountry(self::PAYMENT_SELECTOR_TYPE, $this->paymentCountry);
                        $paymentProvider = $cardPaymentProvider->getIterator()->current()->get();

                        if ($apaymentProvider === false || $paymentProvider->getName() == PaymentProviderEnum::ROYALPAY) {
                            $this->paymentProviderService->setEventsManager($this->eventsManager);
                            $this->eventsManager->attach('orderservice', $this->orderService);
                            $this->paymentProviderService->createOrUpdateDepositTransactionWithPendingStatus($order, $user, $order->getTotal(), $paymentProvider->getName());
                        } else { //Legacy Deposit with only one provider
                            //$cardPaymentProvider = $this->paymentProviderService->createCollectionFromTypeAndCountry(self::PAYMENT_SELECTOR_TYPE, $this->paymentCountry);
                            //$paymentProvider = $cardPaymentProvider->getIterator()->current()->get();
                            $onlyPay = false;
                        }

                        $result_payment = $wallet_service->rechargeWithCreditCard($onlyPay, $paymentProvider, $card, $user, $order, $credit_card_charge);
                        $paymentBodyResponse = $result_payment->returnValues();

                        if ($result_payment->success()) {
                            $converted_net_amount_currency = $this->currencyConversionService->convert($credit_card_charge->getNetAmount(), $user->getUserCurrency());
                            $msg .= 'We added ' . $symbol . ' '  . number_format($converted_net_amount_currency->getAmount() / 100, 2, '.', ',') . ' to your Wallet Balance';
                            if ($credit_card_charge->getIsChargeFee()) {
                                $msg .= ', and charged you an additional '. $site_config_dto->fee .' because it is a transfer below ' . $site_config_dto->feeLimit;
                            }
                            /*
                            echo "
                            <script src='/w/js/vendor/ganalytics.min.js'></script>
                            <script>
                                ga('send', 'event', 'Button', 'Deposit');
                            </script>
                            ";
                            */
                            $credit_card_form->clear();
                            $this->flash->success($msg);
                        } else {
                            $this->flash->error('An error occurred. The response with our payment provider was: ' . $paymentBodyResponse->getMessage());
                        }
                        
                        return (new PaymentRedirectContext($paymentProvider, $order->getLottery()->getName()))->execute($paymentBodyResponse);
                    } catch (\Exception $e) {
                        $this->flash->error($e->getMessage());
                    }
                }
            }
        } else {
            $this->flash->error($this->languageService->translate('signup_emailconfirm') . '<br>'  . $this->languageService->translate('signup_emailresend'));
        }

        $this->response->redirect('/account/wallet');
    }

    public function getCashierAction()
    {
        $this->noRender();
        try {
            if ($this->request->isPost()) {
                $money = new Money(0, new Currency('EUR'));
                $amount = new Money(intval($this->request->getPost('amount')), new Currency('EUR'));
                $type = $this->request->getPost('type');

                $user_id = $this->authService->getCurrentUser()->getId();
                $user = $this->userService->getUser($user_id);

                $playconfig = new PlayConfig();
                $playconfig->setFrequency(1);
                $playconfig->setUser($user);

                $depositLottery = new Lottery();
                $depositLottery->initialize([
                    'id' => 1,
                    'name' => 'Deposit',
                    'active' => 1,
                    'frequency' => 'freq',
                    'draw_time' => 'draw',
                    'single_bet_price' => new Money(23500, new Currency('EUR')),
                ]);
                $order = OrderFactory::create([$playconfig], $amount, $money, $money, new Discount(0, []), $depositLottery, 'Deposit', false);

                $this->cartPaymentProvider = $this->paymentProviderService->createCollectionFromTypeAndCountry($type, $this->paymentCountry);
                $orderDataToPaymentProvider = $this->paymentProviderService->orderDataPaymentProvider($this->cartPaymentProvider->getIterator()->current()->get(), new UserDTO($user), $order, ['isMobile' => SiteHelpers::detectDevice()], $this->di->get('config'));
                $cashierViewDTO = $this->paymentProviderService->cashier($this->cartPaymentProvider->getIterator()->current()->get(), $orderDataToPaymentProvider);

                $this->paymentProviderService->setEventsManager($this->eventsManager);
                $this->eventsManager->attach('orderservice', $this->orderService);
                $this->paymentProviderService->createOrUpdateDepositTransactionWithPendingStatus($order, $user, $order->getTotal(), $this->cartPaymentProvider->getIterator()->current()->get()->getName());

                echo json_encode($cashierViewDTO);
            } else {
                throw new \Exception('Method not allowed');
            }
        } catch (\Exception $e) {
            echo json_encode(
                new ChasierDTO(null, null, null, true, $e->getMessage())
            );
        }
    }
}
