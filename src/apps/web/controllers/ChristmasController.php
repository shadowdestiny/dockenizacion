<?php

namespace EuroMillions\web\controllers;

use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\components\ViewHelper;
use EuroMillions\web\entities\User;
use EuroMillions\web\forms\CreditCardForm;
use EuroMillions\web\services\factories\OrderFactory;
use EuroMillions\web\vo\CardHolderName;
use EuroMillions\web\vo\CardNumber;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\CVV;
use EuroMillions\web\vo\Discount;
use EuroMillions\web\vo\dto\OrderPaymentProviderDTO;
use EuroMillions\web\vo\ExpiryDate;
use Money\Currency;
use Money\Money;

class ChristmasController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $single_bet_price = $this->lotteryService->getSingleBetPriceByLottery('Christmas');
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrency($this->lotteryService->getNextJackpot('Christmas'));
        $this->view->setVar('jackpot_value', $jackpot);
        if (!$this->authService->isLogged()) {
            $user_currency = $this->userPreferencesService->getCurrency();
            $single_bet_price_currency = $this->currencyConversionService->convert($single_bet_price, $user_currency);
        } else {
            $current_user_id = $this->authService->getCurrentUser()->getId();
            /** @var User $user */
            $user = $this->userService->getUser($current_user_id);
            $user_currency = $user->getUserCurrency();
            $single_bet_price_currency = $this->currencyConversionService->convert($single_bet_price, new Currency($user->getUserCurrency()->getName()));
        }
        $currency_symbol = $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'];

        $this->tag->prependTitle($this->languageService->translate('play_ch_name'));
        MetaDescriptionTag::setDescription($this->languageService->translate('play_ch_desc'));



        return $this->view->setVars([
            'currencySymbol' => $currency_symbol,
            'singleBetPrice' => $single_bet_price_currency->getAmount() / 100,
            'christmasTickets' => $this->christmasService->getAvailableTickets(),
            'awardBillionChristmas' => $this->currencyConversionService->convert(new Money(23, new Currency('EUR')), $user_currency)->getAmount() / 10,
            'device' => $this->detectDevice(),
            'pageController' => 'christmasPlay',
        ]);
    }

    public function orderAction()
    {
        if (!$this->request->getPost()) {
            $this->response->redirect('/christmas-lottery/play');
        }

        $user_id = $this->request->get('user');
        $current_user_id = $this->authService->getCurrentUser()->getId();
        $creditCardForm = new CreditCardForm();
        $formErrors = $this->getErrorsArray();
        $errors = [];

        if (!empty($user_id)) {
            $user = $this->userService->getUser($current_user_id);
        } else {
            /** @var User $user */
            $user = $this->userService->getUser($current_user_id);
            if (!$user) {
                $this->response->redirect('/cart/profile');
                return false;
            }
        }

        $locale = $this->request->getBestLanguage();
        $user_currency = $user->getUserCurrency();
        $single_bet_price = $this->domainServiceFactory->getLotteryService()->getSingleBetPriceByLottery('Christmas');
        $single_bet_price_currency = $this->currencyConversionService->convert($single_bet_price, new Currency($user_currency->getName()));
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
        $nextDrawDate = $this->lotteryService->getNextDrawByLottery('Christmas')->returnValues()->getDrawDate();
        $dayDraw = $this->languageService->translate($nextDrawDate->format('l'));
        $nextDrawDate = $nextDrawDate->format('d.m.Y');

        $playConfigs = $play_service->getChristmasPlaysFromTemporarilyStorage($user)->returnValues();
        $draw = $this->lotteryService->getNextDateDrawByLottery('Christmas');
        $order = OrderFactory::create($playConfigs,
                                      $single_bet_price,
                                      new Money(0, new Currency('EUR')),
                                      new Money(0, new Currency('EUR')),
                                      new Discount(0, 0),
                                      $this->lotteryService->getLotteryConfigByName('Christmas'),
                                      $draw,
                                      $checked_wallet
        );
        $this->cartService->store($order);

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
            'christmasTickets' => $playConfigs,
            'payTotalWithWallet' => (($total_price - $wallet_balance->getAmount()) / 100 <= 0) ? 1 : 0, // 1 true, 0 false
            'priceWithWallet' => ($total_price - $wallet_balance->getAmount()) / 100,
            'emerchant_data' => $this->getEmerchantData(),
            'nextDrawDate' => $nextDrawDate,
            'dayDraw' => $dayDraw,
        ]);
    }

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
        $play_service = $this->domainServiceFactory->getPlayService();
        $errors = [];
        $user_id = $this->authService->getCurrentUser()->getId();
        /** @var User $user */
        $user = $this->userService->getUser($user_id);

        if (null == $user) {
            $this->response->redirect('/' . $this->lottery . '/cart/profile');
            return false;
        }
        $result = $play_service->getChristmasPlaysFromTemporarilyStorage($user);

        $msg = '';

        $order_view = true;
        //Payment thru wallet ONLY
        if ($this->request->isGet()) {
            $isWallet = true;
            $charge = 0;
            $user = $this->userService->getUser($user_id);
            if (null != $user && isset($charge)) {
                try {
                    $card = null;
                    $amount = new Money((int)$charge, new Currency('EUR'));
                    $result = $play_service->playChristmas($user_id, $amount, $card, true, $isWallet, $result->getValues());
                    return $this->playResult($result);
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
                        $result = $play_service->playChristmas($user_id, $amount, $card, $payWallet, $isWallet, $result->getValues());

                        return $this->playResult($result);
                    } catch (\Exception $e) {
                        $errors[] = $e->getMessage();
                    }
                }
            }
        }

        $this->response->redirect('/christmas/failure');
    }

    public function successAction()
    {
        $user_id = $this->authService->getCurrentUser()->getId();
        /** @var User $user */
        $user = $this->userService->getUser($user_id);

        if (null == $user) {
            $this->response->redirect('/' . $this->lottery . '/cart/profile');
            return false;
        }

        $play_service = $this->domainServiceFactory->getPlayService();
        $nextDrawDate = $this->lotteryService->getNextDrawByLottery('Christmas')->returnValues()->getDrawDate();
        $dayDraw = $this->languageService->translate($nextDrawDate->format('l'));
        $this->tag->prependTitle('Purchase Confirmation');
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot('Christmas'));

        $numbers = preg_replace('/[A-Z,€,.]/','',ViewHelper::formatJackpotNoCents($jackpot));
        $letters = preg_replace('/[0-9.,]/','',ViewHelper::formatJackpotNoCents($jackpot));

        $this->view->setVar('milliards', false);
        $this->view->setVar('trillions', false);
        if ($numbers > 1000 && $this->languageService->getLocale() != 'es_ES') {
            $numbers = round(($numbers / 1000), 1);
            $this->view->setVar('jackpot_value', $letters . ' ' . $numbers);
            $this->view->setVar('milliards', true);
        } elseif ($numbers > 1000000 && $this->languageService->getLocale() != 'es_ES') {
            $numbers = round(($numbers / 1000000), 1);
            $this->view->setVar('jackpot_value', $letters . ' ' . $numbers);
            $this->view->setVar('trillions', true);
        } else{
            $this->view->setVar('milliards', false);
            $this->view->setVar('trillions', false);
        }
        $this->view->setVar('jackpot_value_success', ViewHelper::formatJackpotNoCents($jackpot));
        $linkPlay = 'link_christmas_play';

        return $this->view->setVars([
            'jackpot_value' => '2.3',
            'user' => $user,
            'draw_date_format' => $this->lotteryService->getNextDateDrawByLottery('Christmas')->format('Y-m-d'),
            'christmasTickets' => $play_service->getChristmasPlaysFromTemporarilyStorage($user)->returnValues(),
            'draw_day' => $dayDraw,
            'lottery_name' => 'Christmas'
        ]);
    }

    public function failureAction()
    {
        $user_id = $this->authService->getCurrentUser()->getId();
        $play_service = $this->domainServiceFactory->getPlayService();
        $play_service->removeStorePlay($user_id);
        $play_service->removeStoreOrder($user_id);
        $this->tag->prependTitle('Payment Unsuccessful');
    }

    /**
     * @return array
     */
    private function getErrorsArray()
    {
        $form_errors = [
            'email' => '',
            'password' => '',
            'name' => '',
            'surname' => '',
            'confirm_password' => '',
            'country' => '',
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

    /**
     * @param ActionResult $result
     * @return bool
     */
    protected function playResult(ActionResult $result)
    {
        if ($result->success()) {
            $this->response->redirect('/christmas/success');
            return false;
        } else {
            $this->response->redirect('/christmas/failure');
            return false;
        }
    }

    public function detectDevice()
    {
        $tablet_browser = 0;
        $mobile_browser = 0;
        $body_class = 'desktop';

        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $tablet_browser++;
            $body_class = "tablet";
        }

        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $mobile_browser++;
            $body_class = "mobile";
        }

        if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
            $mobile_browser++;
            $body_class = "mobile";
        }

        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = array(
            'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
            'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
            'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
            'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
            'newt', 'noki', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
            'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
            'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
            'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
            'wapr', 'webc', 'winw', 'winw', 'xda ', 'xda-');

        if (in_array($mobile_ua, $mobile_agents)) {
            $mobile_browser++;
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'opera mini') > 0) {
            $mobile_browser++;
            //Check for tablets on opera mini alternative headers
            $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : (isset($_SERVER['HTTP_DEVICE_STOCK_UA']) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : ''));
            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
                $tablet_browser++;
            }
        }
        if ($tablet_browser > 0) {
            return 2;
        } else if ($mobile_browser > 0) {
            return 1;
        } else {
            return 3;
        }
    }
}
