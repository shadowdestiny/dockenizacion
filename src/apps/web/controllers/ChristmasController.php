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
        if (!$this->authService->isLogged()) {
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
        if (!$this->request->getPost()) {
            $this->response->redirect('/christmas/play');
        }

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
                $this->response ->redirect('/cart/profile');
                return false;
            }
        }

        $locale = $this->request->getBestLanguage();
        $user_currency = $user->getUserCurrency();
        $single_bet_price = $this->domainServiceFactory->getLotteryService()->getSingleBetPriceByLottery('Christmas');
        $single_bet_price_currency  = $this->currencyConversionService->convert($single_bet_price, new Currency($user_currency->getName()));
        $user = $this->authService->getCurrentUser();

        $wallet_balance = $this->currencyConversionService->convert($user->getBalance(), $user_currency);
        $checked_wallet = ($wallet_balance->getAmount() && !$this->request->isPost()) > 0 ? true : false;

        $christmasTickets = $this->christmasService->getChristmasTicketsData($this->request->getPost());
        $total_price = ($this->currencyConversionService->convert($single_bet_price, $user_currency)->getAmount() * count($christmasTickets));

        $symbol_position = $this->currencyConversionService->getSymbolPosition($locale, $user_currency);
        $currency_symbol = $this->currencyConversionService->getSymbol($wallet_balance, $locale);
        $ratio = $this->currencyConversionService->getRatio(new Currency('EUR'), $user_currency);

        $play_service = $this->domainServiceFactory->getPlayService();
        $play_service->saveChristmasPlay($christmasTickets, $user->getId());

        $this->tag->prependTitle('Review and Buy');

        return $this->view->setVars([
            'wallet_balance' => number_format((float)$wallet_balance->getAmount() / 100, 2, '.', ''),
            'total_price' => number_format((float)$total_price / 100, 2, '.', ''),
            'single_bet_price' => $single_bet_price_currency->getAmount() / 100,
            'form_errors' => $formErrors,
            'ratio' => $ratio,
            'msg' => [],
            'currency_symbol' => $currency_symbol,
            'symbol_position' => ($symbol_position === 0) ? false : true,
            'errors' => $errors,
            'show_form_credit_card' => (!empty($errors)) ? true : false,
            'checked_wallet' => $checked_wallet,
            'email' => $user->getEmail()->toNative(),
            'total_new_payment_gw' => isset($order_eur) ? $order_eur->getTotal()->getAmount() / 100 : '',
            'credit_card_form' => $creditCardForm,
            'christmasTickets' => $play_service->getChristmasPlaysFromTemporarilyStorage($user)->returnValues(),
            'payTotalWithWallet' => ( ($total_price - $wallet_balance->getAmount()) / 100 <= 0 ) ? 1 : 0, // 1 true, 0 false
            'priceWithWallet' => ($total_price - $wallet_balance->getAmount()) / 100 ,
            'emerchant_data' => $this->getEmerchantData(),
        ]);
    }

    public function paymentAction()
    {
        $christmasTickets = $this->fakeTickets();
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
    
    private function fakeTickets()
    {
        return [
            [
                'id' => 1,
                'tickets' => 2,
            ],[
                'id' => 3,
                'tickets' => 1,
            ],[
                'id' => 5,
                'tickets' => 3,
            ],[
                'id' => 8,
                'tickets' => 1,
            ]
        ];
    }
}
