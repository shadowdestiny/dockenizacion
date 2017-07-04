<?php

namespace EuroMillions\web\controllers;

use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\components\ViewHelper;
use EuroMillions\web\entities\User;
use EuroMillions\web\forms\CreditCardForm;
use EuroMillions\web\vo\dto\PlayConfigCollectionDTO;
use EuroMillions\web\vo\Order;
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
        ]);
    }

    public function orderAction()
    {
        $user_id = $this->request->get('user');
        $current_user_id = $this->authService->getCurrentUser()->getId();
        $creditCardForm = new CreditCardForm();
        $formErrors = $this->getErrorsArray();
        $msg = '';
        $errors = [];

        if(!empty($user_id)) {
            $user = $this->userService->getUser($current_user_id);
        } else {
            /** @var User $user */
            $user = $this->userService->getUser($current_user_id);
            if(!$user) {
                $this->response->redirect('/christmas/play');
                return false;
            }
        }


        $locale = $this->request->getBestLanguage();
        $user_currency = $user->getUserCurrency();
        $single_bet_price = $this->domainServiceFactory->getLotteryService()->getSingleBetPriceByLottery('Christmas');
        $user = $this->authService->getCurrentUser();
        $discount = 0;

        $order = new Order($result->returnValues(), $single_bet_price, $fee_value, $fee_to_limit_value, $discount); // order created
        $order_eur = new Order($result->returnValues(), $single_bet_price, $this->siteConfigService->getFee(), $this->siteConfigService->getFeeToLimitValue(), $discount); //workaround for new payment gateway
        $this->cartService->store($order);

        /** @var PlayConfig[] $play_config */
        $play_config_collection = $result->returnValues();
        $play_config_dto = new PlayConfigCollectionDTO($play_config_collection, $single_bet_price);
        $wallet_balance = $this->currencyConversionService->convert($play_config_dto->wallet_balance_user, $user_currency);
        $checked_wallet = ($wallet_balance->getAmount() && !$this->request->isPost()) > 0 ? true : false;
        $play_config_dto->single_bet_price_converted = $this->currencyConversionService->convert($play_config_dto->single_bet_price, $user_currency);
        $play_config_dto->singleBetPriceWithDiscountConverted = $this->currencyConversionService->convert($play_config_dto->singleBetPriceWithDiscount, $user_currency);
        //convert to user currency
        $total_price = ($this->currencyConversionService->convert($play_config_dto->singleBetPriceWithDiscount, $user_currency)->getAmount() * $play_config_dto->numPlayConfigs) * $play_config_dto->frequency;
        $symbol_position = $this->currencyConversionService->getSymbolPosition($locale, $user_currency);
        $currency_symbol = $this->currencyConversionService->getSymbol($wallet_balance, $locale);
        $ratio = $this->currencyConversionService->getRatio(new Currency('EUR'), $user_currency);
        $this->tag->prependTitle('Review and Buy');

        return $this->view->setVars([
            'order' => $play_config_dto,
            'wallet_balance' => $wallet_balance->getAmount() / 100,
            'total_price' => $total_price / 100,
            'form_errors' => $formErrors,
            'ratio' => $ratio,
            'singleBetPriceWithDiscount' => $play_config_dto->singleBetPriceWithDiscount->getAmount(),
            'fee_limit' => $fee_to_limit_value->getAmount() / 100,
            'fee' => $fee_value->getAmount() / 100,
            'discount' => $discount->getValue(),
            'currency_symbol' => $currency_symbol,
            'symbol_position' => ($symbol_position === 0) ? false : true,
            'message' => (!empty($msg)) ? $msg : '',
            'errors' => $errors,
            'show_form_credit_card' => (!empty($errors)) ? true : false,
            'msg' => [],
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
