<?php

namespace EuroMillions\web\controllers;

use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\entities\User;
use EuroMillions\web\forms\CreditCardForm;
use Money\Currency;

class ChristmasController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $single_bet_price = $this->lotteryService->getSingleBetPriceByLottery('Christmas');
        if(!$this->authService->isLogged()) {
            $user_currency = $this->userPreferencesService->getCurrency();
            $single_bet_price_currency  = $this->currencyConversionService->convert($single_bet_price, $user_currency);
        } else {
            $current_user_id = $this->authService->getCurrentUser()->getId();
            /** @var User $user */
            $user = $this->userService->getUser($current_user_id);
            $single_bet_price_currency  = $this->currencyConversionService->convert($single_bet_price, new Currency($user->getUserCurrency()->getName()));
        }
        $currency_symbol = $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'];

        $this->tag->prependTitle('Play Christmas Lottery ');
        MetaDescriptionTag::setDescription('Play Christmas Lottery worldwide on the official website of EuroMillions.com and become our next EuroMillionaire!');

        return $this->view->setVars([
            'currencySymbol' => $currency_symbol,
            'singleBetPrice' => $single_bet_price_currency->getAmount() /100,
            'christmasTickets' => $this->christmasService->getAvailableTickets(),
        ]);
    }

    public function orderAction()
    {
        var_dump($this->request->getPost());
        exit;
        $user_id = $this->request->get('user');
        $current_user_id = $this->authService->getCurrentUser()->getId();
        $creditCardForm = new CreditCardForm();
        $formErrors = $this->getErrorsArray();
        $errors = [];

        if(!empty($user_id)) {
            $user = $this->userService->getUser($current_user_id);
        } else {
            /** @var User $user */
            $user = $this->userService->getUser($current_user_id);
            if(!$user) {
                $this->response ->redirect('/christmas/play');
                return false;
            }
        }

        $locale = $this->request->getBestLanguage();
        $user_currency = $user->getUserCurrency();
        $single_bet_price = $this->domainServiceFactory->getLotteryService()->getSingleBetPriceByLottery('Christmas');
        $user = $this->authService->getCurrentUser();
        $discount = 0;

        $wallet_balance = $this->currencyConversionService->convert($user->getBalance(), $user_currency);
        $checked_wallet = ($wallet_balance->getAmount() && !$this->request->isPost()) > 0 ? true : false;

        $total_price = ($this->currencyConversionService->convert($single_bet_price, $user_currency)->getAmount() * $this->request->getPost('totalTickets'));
        $symbol_position = $this->currencyConversionService->getSymbolPosition($locale, $user_currency);
        $currency_symbol = $this->currencyConversionService->getSymbol($wallet_balance, $locale);
        $ratio = $this->currencyConversionService->getRatio(new Currency('EUR'), $user_currency);
        $this->tag->prependTitle('Review and Buy');

        return $this->view->setVars([
            'wallet_balance' => $wallet_balance->getAmount() / 100,
            'total_price' => $total_price / 100,
            'form_errors' => $formErrors,
            'ratio' => $ratio,
            'discount' => $discount->getValue(),
            'currency_symbol' => $currency_symbol,
            'symbol_position' => ($symbol_position === 0) ? false : true,
            'errors' => $errors,
            'show_form_credit_card' => (!empty($errors)) ? true : false,
            'checked_wallet' => $checked_wallet,
            'email' => $user->getEmail()->toNative(),
            'total_new_payment_gw' => isset($order_eur) ? $order_eur->getTotal()->getAmount() / 100 : '',
            'credit_card_form' => $creditCardForm,
            'emerchant_data' => $this->getEmerchantData(),
        ]);
    }

    /**
     * @return array
     */
    private function getErrorsArray()
    {
        $form_errors = [
            'email'            => '',
            'password'         => '',
            'name'             => '',
            'surname'          => '',
            'confirm_password' => '',
            'country'          => '',
            'card-number' => '',
            'card-holder' => '',
            'card-cvv' => '',
            'expiry-date-month' => '',
            'expiry-date-year' => '',
        ];
        return $form_errors;
    }

    /**
     * @return array
     */
    private function getEmerchantData()
    {
        $thm_org_id = 'lygdph9h';
        $client_id = "909524";
        $thm_session_id = $client_id . date('Ymdhis') . rand(100000, 999999);

        return [
            'thm_org_id' => 'lygdph9h',
            'client_id' => "909524",
            'thm_session_id' => $thm_session_id,
            'thm_guid' => md5(rand()),
            'thm_params' => 'org_id=' . $thm_org_id . '&session_id=' . $thm_session_id
        ];
    }

}
