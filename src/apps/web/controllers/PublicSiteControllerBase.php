<?php

namespace EuroMillions\web\controllers;

use EuroMillions\shared\components\widgets\JackpotAndCountDownWidget;
use EuroMillions\shared\components\widgets\LotteryResultsListWidget;
use EuroMillions\shared\helpers\PaginatedControllerTrait;
use EuroMillions\shared\helpers\SiteHelpers;
use EuroMillions\shared\services\SiteConfigService;
use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\components\ViewHelper;
use EuroMillions\web\entities\Language;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\AuthService;
use EuroMillions\web\services\BlogService;
use EuroMillions\web\services\CartService;
use EuroMillions\web\services\ChristmasService;
use EuroMillions\web\services\CurrencyConversionService;
use EuroMillions\web\services\CurrencyService;
use EuroMillions\web\services\LanguageService;
use Doctrine\ORM\EntityManager;
use EuroMillions\web\services\LotteryService;
use EuroMillions\web\services\OrderService;
use EuroMillions\web\services\PaymentProviderService;
use EuroMillions\web\services\PowerBallCartService;
use EuroMillions\web\services\TransactionService;
use EuroMillions\web\services\UserPreferencesService;
use EuroMillions\web\services\UserService;
use EuroMillions\web\vo\dto\CurrencyDTO;
use Money\Currency;


/**
 * Class PublicSiteControllerBase
 * @package EuroMillions\controllers
 * @property EntityManager $entityManager
 * @property LanguageService $language
 */
class PublicSiteControllerBase extends ControllerBase
{

    use PaginatedControllerTrait;

    /** @var LotteryService */
    protected $lotteryService;
    /** @var  LanguageService */
    protected $languageService;
    /** @var  CurrencyService */
    protected $currencyService;
    /** @var  UserService */
    protected $userService;
    /** @var  AuthService */
    protected $authService;
    /** @var  UserPreferencesService */
    protected $userPreferencesService;
    /** @var  SiteConfigService $siteConfigService */
    protected $siteConfigService;
    /** @var  CartService $cartService */
    protected $cartService;
    /** @var  PowerBallCartService $powerBallCartService */
    protected $powerBallCartService;
    /** @var  CurrencyConversionService */
    protected $currencyConversionService;
    /** @var  TransactionService */
    protected $transactionService;
    /** @var  ChristmasService */
    protected $christmasService;
    /** @var  BlogService */
    protected $blogService;

    /** @var PaymentProviderService */
    protected $paymentProviderService;

    protected $lottery;

    protected $languageUrl;

    protected $currencyUrl;

    protected $cartPaymentProvider;

    /** @var OrderService $orderService */
    protected $orderService;

    public function initialize(LotteryService $lotteryService = null,
                               LanguageService $languageService = null,
                               CurrencyService $currencyService = null,
                               UserService $userService = null,
                               AuthService $authService = null,
                               UserPreferencesService $userPreferencesService = null,
                               SiteConfigService $siteConfigService = null,
                               CartService $cartService = null,
                               CurrencyConversionService $currencyConversionService = null,
                               TransactionService $transactionService = null,
                               ChristmasService $christmasService = null,
                               BlogService $blogService = null,
                               PowerBallCartService $powerBallCartService = null)
    {
        parent::initialize();
        $this->lotteryService = $lotteryService ?: $this->domainServiceFactory->getLotteryService();
        $this->languageService = $languageService ?: $this->language; //from DI
        $this->currencyService = $currencyService ?: $this->domainServiceFactory->getCurrencyService();
        $this->userService = $userService ?: $this->domainServiceFactory->getUserService();
        $this->authService = $authService ?: $this->domainServiceFactory->getAuthService();
        $this->userPreferencesService = $userPreferencesService ?: $this->domainServiceFactory->getUserPreferencesService();
        $this->cartService = $cartService ?: $this->domainServiceFactory->getCartService();
        $this->powerBallCartService = $powerBallCartService ?: $this->domainServiceFactory->getPowerBallCartService();
        $this->currencyConversionService = $currencyConversionService ?: $this->domainServiceFactory->getCurrencyConversionService();
        $this->siteConfigService = $siteConfigService ?: new SiteConfigService($this->di->get('entityManager'), $this->currencyConversionService);
        $this->transactionService = $transactionService ?: new TransactionService($this->di->get('entityManager'), $this->currencyConversionService);
        $this->christmasService = $christmasService ?: new ChristmasService($this->di->get('entityManager'));
        $this->blogService = $blogService ?: new BlogService($this->di->get('entityManager'));
        $this->lottery = !isset($this->router->getParams()['lottery']) ? 'euromillions' : $this->router->getParams()['lottery'];
        $this->languageUrl = !isset($this->router->getParams()['language']) ? '' : $this->router->getParams()['language'];
        $this->currencyUrl = !isset($this->router->getParams()['currency']) ? '' : $this->router->getParams()['currency'];
        $this->cartPaymentProvider = $this->getDI()->get('paymentProviderFactory');
        $this->paymentProviderService = $this->di->get('domainServiceFactory')->getPaymentProviderService();
        $this->orderService = $this->domainServiceFactory->getOrderService();
    }

    public function afterExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {
        $this->setResultWidget();
        $this->setJackpotWidget();
        $this->checkAuth();
        $this->setCurrencyByUrl();
        $this->setActiveLanguages();
        $this->setActiveCurrencies();
        $this->setTopNavValues();
        $this->setNavValues();
        $this->setCommonTemplateVariables();
        $this->setVarWinningModal();


        // Set up the flash session service
        $controller_not_referer = [
            'user-access',
            'currency',
            'user-settings',
            'account',
            'password'
        ];

        if (!in_array($dispatcher->getControllerName(), $controller_not_referer, false)) {
            $this->session->set('original_referer', $this->router->getRewriteUri());
        }
        //To avoid clickjacking, add it in ngnix configuration
        $this->response->setHeader('X-Frame-Options', 'SAMEORIGIN');
    }

    public function checkAuth()
    {
        $this->authService->tryLoginWithRemember();
    }

    protected function setJackpotWidget()
    {
        $controllerWidgetForLotteries = $this->di->get('config')['jackpot_widget'];
        $widgetArr = null;
        $count = 1;
        $numLotteries = count($controllerWidgetForLotteries);
        foreach($controllerWidgetForLotteries as $lottery => $controllers)
        {
            foreach(explode(',',$controllers) as $controller) {
                if($this->dispatcher->getControllerName() ==  $controller)
                {
                    $jackpot = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot($lottery));
                    $numbers = preg_replace('/[A-Z,.]/','',ViewHelper::formatJackpotNoCents($jackpot));
                    $letters = preg_replace('/[0-9.,]/','',ViewHelper::formatJackpotNoCents($jackpot));
                    $params = ViewHelper::setSemanticJackpotValue($numbers, $letters, $jackpot, $this->languageService->getLocale());
                    $params['show_s_days'] = (new \DateTime())->diff($this->lotteryService->getNextDateDrawByLottery($lottery)->modify('-1 hours'))->format('%a');
                    $params['date_draw'] = $this->lotteryService->getNextDateDrawByLottery($lottery)->modify('-1 hours')->format('Y-m-d H:i:s');
                    if($widgetArr != null) $widgetArr .= '<br>';
                    if($numLotteries == $count) $params['last'] = true;
                    $widgetArr .= (new JackpotAndCountDownWidget($params['jackpot_value'],
                        $this->lotteryService->getLotteryConfigByName($lottery),
                        $params))->render();
                    $count++;
                }
            }
        }
        $this->view->setVar('jackpot_widget', $widgetArr);
    }

    protected function setResultWidget()
    {
        $controllerWidgetForLotteries = $this->di->get('config')['result_widget'];
        $widgetArr = null;
        if (!$controllerWidgetForLotteries) {
            return $this->view->setVar('result_widget', $widgetArr);
        }

        foreach($controllerWidgetForLotteries as $lottery => $controllers)
        {
            foreach(explode(',',$controllers) as $controller) {
                if($this->dispatcher->getControllerName() ==  $controller)
                {
                    $widgetArr .= (new LotteryResultsListWidget($lottery))->render();
                }
            }
        }
        $this->view->setVar('result_widget', $widgetArr);
    }

    protected function setTopNavValues()
    {
        $user_currency = $this->userPreferencesService->getMyCurrencyNameAndSymbol();
        $current_currency = $this->userPreferencesService->getCurrency();
        $is_logged = $this->authService->isLogged();

        if ($is_logged) {
            $user = $this->authService->getCurrentUser();
            $user = $this->userService->getUser($user->getId());
            $currency = $this->userPreferencesService->getCurrency();
            if ($user->getUserCurrency()->getName() != $currency->getName()) {
                $this->userPreferencesService->setCurrency($user->getUserCurrency());
                $user_currency = $this->userPreferencesService->getMyCurrencyNameAndSymbol();
            }
            $user_balance = $this->userService->getBalanceWithUserCurrencyConvert($this->authService->getCurrentUser()->getId(), $this->userPreferencesService->getCurrency());
            $user_balance_raw = $this->currencyConversionService->convert($user->getBalance(), $this->userPreferencesService->getCurrency())->getAmount();

            if (!$this->session->get('lastConnectionTime') || ($this->session->get('lastConnectionTime') < date('Y-m-d H:i:s'))) {
                $this->userService->updateLastConnection($user);
                $this->session->set('lastConnectionTime', date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +1 day')));
            }
            $user_language = $user->getDefaultLanguage();
        } else {
            $user_balance = '';
            $user_balance_raw = '';
            $user_language = explode('_', $this->languageService->getLocale())[0];
        }
        $this->view->setVar('lottery', $this->lottery);
        $this->view->setVar('current_currency', $current_currency->getName());
        $this->view->setVar('user_currency', $user_currency);
        $this->view->setVar('user_currency_code', $current_currency->getName());
        $this->view->setVar('user_balance', $user_balance);
        $this->view->setVar('user_balance_raw', $user_balance_raw);
        $this->view->setVar('active_languages', $this->languageService->activeLanguagesArray());
        $this->view->setVar('user_language', $user_language);
        //$this->view->setVar('jackpot', $this->userPreferencesService->getJackpotInMyCurrency($this->lotteryService->getNextJackpot('EuroMillions')));
        $date_time_util = new DateTimeUtil();
        $date_next_draw = $this->lotteryService->getNextDateDrawByLottery('EuroMillions');
        $this->view->setVar('nextDrawDateEuromillions', $date_next_draw->format('Y-m-d'));
        $this->view->setVar('countdown_next_draw', $date_time_util->getCountDownNextDraw($date_next_draw));
        //EMTD create a method helper to set this vars
        $this->view->setVar('countdown_finish_bet', []/*ViewHelper::setCountDownFinishBet(30, 10, 5, $date_next_draw)*/);
        //This is only for functional test
        if (!empty($this->request->get('fakedatetime'))) {
            $fakeDateTime = new \DateTime($this->request->get('fakedatetime'));
            $this->view->setVar('countdown_finish_bet', ViewHelper::setCountDownFinishBet(1, 100, 5, $this->lotteryService->getNextDateDrawByLottery('EuroMillions', new \DateTime('2016-11-11 18:00:00')), $fakeDateTime->setTime(17, 9, 58)));
        }
        $mainJackpotHomeDTO = $this->lotteryService->mainJackpotHome();
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($mainJackpotHomeDTO->jackpot);
        $numbers = preg_replace('/[A-Z,.]/','',ViewHelper::formatJackpotNoCents($jackpot));
        $letters = preg_replace('/[0-9.,]/','',ViewHelper::formatJackpotNoCents($jackpot));
        $params = ViewHelper::setSemanticJackpotValue($numbers, $letters, $jackpot, $this->languageService->getLocale());
        $this->view->setVar('milliards', $params['milliards']);
        $this->view->setVar('trillions', $params['trillions']);
        $this->view->setVar('jackpot_value', $params['jackpot_value']);
        $this->view->setVar('language', $this->languageService->getLocale());

        $single_bet_price = $this->lotteryService->getSingleBetPriceByLottery('EuroMillions');
        $single_bet_price_currency = $this->currencyConversionService->convert($single_bet_price, $current_currency);
        $bet_value = $this->currencyConversionService->toString($single_bet_price_currency, $current_currency);
        $single_bet_price_currency_gbp = $this->currencyConversionService->convert($single_bet_price, new Currency('GBP'));
        $bet_value_pound = $this->currencyConversionService->toString($single_bet_price_currency_gbp, new Currency('GBP'));
        $this->view->setVar('bet_price', $bet_value);
        $this->view->setVar('bet_price_pound', $bet_value_pound);
    }

    private function setCurrencyByUrl()
    {
        if (!$this->authService->isLogged() &&  !$this->userPreferencesService->existCurrency())
        {
            if($this->languageUrl=='ru')
            {
                $this->userPreferencesService->setCurrency(new Currency('RUB'));
            }
            elseif ($this->currencyUrl!='')
            {
                $this->userPreferencesService->setCurrency(new Currency($this->currencyUrl));
            }
            else
            {
                $this->userPreferencesService->setCurrency(new Currency('EUR'));
            }
        }
    }

    private function setNavValues()
    {
        $is_logged = $this->authService->isLogged();
        if ($is_logged) {
            /** @var User $user */
            $user = $this->authService->getCurrentUser();
            $user_name = $user->getName();
        } else {
            $user_name = '';
        }

        $isMobile = SiteHelpers::detectDevice();
        $this->view->setVar('mobile', $isMobile);
        $this->view->setVar('user_logged', $is_logged);
        $this->view->setVar('user_name', $user_name);

    }

    private function setActiveCurrencies()
    {
        $currencies = $this->currencyService->getCurrenciesMostImportant();
        $currencies_list = $this->currencyService->getActiveCurrenciesCodeAndNames();
        if ($currencies->success()) {
            $currencies_dto = [];
            foreach ($currencies->getValues() as $currency) {
                $currencies_dto[] = new CurrencyDTO($currency);
            }
            $this->view->setVars(['currencies' => $currencies_dto]);
            $currencies_dto = [];
            foreach ($currencies_list->getValues() as $currency) {
                $currencies_dto[] = new CurrencyDTO($currency);
            }
            $this->view->setVars(['currency_list' => $currencies_dto]);
        }
    }

    private function setActiveLanguages()
    {
        $languages = $this->languageService->activeLanguages();
        $view_params = [];
        /** @var Language $language */
        foreach ($languages as $language) {
            $view_params[] = $language->toValueObject();
        }
        $this->view->setVars(['languages' => $view_params]);
    }

    private function setCommonTemplateVariables()
    {
        $this->view->setVar('currency_symbol_first', true);
    }

    private function setVarWinningModal()
    {
        $is_logged = $this->authService->isLogged();
        if ($is_logged) {
            $user_id = $this->authService->getCurrentUser();
            /** @var User $user */
            $user = $this->userService->getUser($user_id->getId());
            if ($user->getShowModalWinning()) {
                $this->view->setVar('show_modal_winning', true);
                $user->setShowModalWinning(false);
                $this->userService->updateUser($user);
            } else {
                $this->view->setVar('show_modal_winning', false);
            }
        } else {
            $this->view->setVar('show_modal_winning', false);
        }
    }


}
